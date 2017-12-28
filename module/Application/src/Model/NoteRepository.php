<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class NoteRepository
{
    protected $sql;

    protected $noteHydrator;

    public function __construct(AdapterInterface $dbAdapter, NoteHydrator $noteHydrator)
    {
        $this->sql = new Sql($dbAdapter);
        $this->noteHydrator = $noteHydrator;
    }

    public function findAll()
    {
        $notes = [];
        $noteSelect = new Select('note');
        $noteResult = $this->sql->prepareStatementForSqlObject($noteSelect)->execute();

        $noteResultSet = new HydratingResultSet($this->noteHydrator, new Note());

        $noteResultSet->initialize($noteResult);

        /**
         * @var Note $note
         */
        foreach ($noteResultSet as $note) {
            $categorySelect = (new Select('category'))
                ->where(['id' => $note->getCategory()->getId()]);

            $categoryResult = $this->sql->prepareStatementForSqlObject($categorySelect)->execute();
            $categoryResultSet = new HydratingResultSet(new ReflectionHydrator(), new Category());
            $categoryResultSet->initialize($categoryResult);

            /** @var Category $category */
            $category = $categoryResultSet->current();
            $note->setCategory($category);
            $notes[] = $note;
        }

        return $notes;
    }

    public function findById($id): Note
    {
        $noteSelect = (new Select('note'))->where(['id' => $id]);
        $noteResult = $this->sql->prepareStatementForSqlObject($noteSelect)->execute();

        if (!$noteResult->valid()) {
            throw new \UnexpectedValueException('Note not found');
        }

        $noteResultSet = new HydratingResultSet($this->noteHydrator, new Note());
        $noteResultSet->initialize($noteResult);

        /** @var Note $note */
        $note = $noteResultSet->current();

        $tagSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', [])
            ->join(['n' => 'note'], 'n.id = nt.note_id', [])
            ->where(['n.id' => $id]);

        $tagResult = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();
        $tagResultSet = new HydratingResultSet(new ReflectionHydrator(), new Tag());
        $tagResultSet->initialize($tagResult);

        /**
         * @var Tag $tag
         */
        foreach($tagResultSet as $tag) {
            $note->addTag($tag);
        }

        return $note;
    }
}
