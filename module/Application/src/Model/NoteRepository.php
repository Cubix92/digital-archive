<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\HydratingResultSet;
use Zend\Db\Sql\Select;
use Zend\Hydrator\Reflection as ReflectionHydrator;

class NoteRepository extends AdapterAbstract
{
    protected $noteHydrator;

    public function __construct(NoteHydrator $noteHydrator, AdapterInterface $dbAdapter)
    {
        parent::__construct($dbAdapter);
        $this->noteHydrator = $noteHydrator;
    }

    public function findAll()
    {
        $notes = [];
        $noteSelect = new Select('note');
        $noteResult = $this->executeStatement($noteSelect);
        $noteResultSet = new HydratingResultSet($this->noteHydrator, new Note());

        $noteResultSet->initialize($noteResult);

        /**
         * @var Note $note
         */
        foreach ($noteResultSet as $note) {
            $categorySelect = (new Select('category'))
                ->where(['id' => $note->getCategory()->getId()]);

            $categoryResult = $this->executeStatement($categorySelect);
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
        $noteSelect = $this->sql->select('note')->where(['id' => $id]);
        $noteResult = $this->executeStatement($noteSelect);

        if (!$noteResult->valid()) {
            throw new \InvalidArgumentException(sprintf('Note with identifier "%s" not found', $id));
        }

        $noteResultSet = new HydratingResultSet($this->noteHydrator, new Note());
        $noteResultSet->initialize($noteResult);

        /** @var Note $note */
        $note = $noteResultSet->current();

        $tagSelect = $this->sql->select(['t' => 'tag'])
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', [])
            ->join(['n' => 'note'], 'n.id = nt.note_id', [])
            ->where(['n.id' => $id]);

        $tagResult = $this->executeStatement($tagSelect);
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
