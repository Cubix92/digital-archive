<?php

namespace Application\Model;

use Zend\Db\Adapter\AdapterInterface;
use Zend\Db\ResultSet\ResultSet;
use Zend\Db\Sql\Select;
use Zend\Db\Sql\Sql;

class NoteRepository
{
    protected $sql;

    protected $noteHydrator;

    public function __construct(AdapterInterface $dbAdapter, NoteHydrator $noteHydrator)
    {
        $this->sql = new Sql($dbAdapter);
        $this->noteHydrator = $noteHydrator;
    }

    public function findAll(): array
    {
        $notes = [];
        $noteSelect = new Select('note');
        $noteResult = $this->sql->prepareStatementForSqlObject($noteSelect)->execute();

        $noteResultSet = new ResultSet();
        $noteResultSet->initialize($noteResult);

        /**
         * @var Note $note
         */
        foreach ($noteResultSet->toArray() as $noteSet) {
            $categorySelect = (new Select('category'))
                ->where(['id' => $noteSet['category']]);

            $categoryResult = $this->sql->prepareStatementForSqlObject($categorySelect)->execute();
            $categoryResultSet = new ResultSet();
            $categoryResultSet->initialize($categoryResult);

            /** @var Category $category */
            $category = $categoryResultSet->current();
            $noteSet['category'] = (array)$category;

            $tagSelect = (new Select(['t' => 'tag']))
                ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', [])
                ->join(['n' => 'note'], 'n.id = nt.note_id', [])
                ->where(['n.id' => $noteSet['id']]);

            $tagResult = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();
            $tagResultSet = new ResultSet();
            $tagResultSet->initialize($tagResult);

            /** @var Tag $tag */
            foreach($tagResultSet->toArray() as $tagSet) {
                $noteSet['tags'][] = $tagSet;
            }

            $notes[] = $this->noteHydrator->hydrate($noteSet, new Note());
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

        $noteResultSet = new ResultSet();
        $noteResultSet->initialize($noteResult);

        $note = (array)$noteResultSet->current();

        $categorySelect = (new Select('category'))
            ->where(['id' => $note['category']]);

        $categoryResult = $this->sql->prepareStatementForSqlObject($categorySelect)->execute();
        $categoryResultSet = new ResultSet();
        $categoryResultSet->initialize($categoryResult);

        $category = $categoryResultSet->current();
        $note['category'] = (array)$category;

        $tagSelect = (new Select(['t' => 'tag']))
            ->join(['nt' => 'note_tag'], 'nt.tag_id = t.id', [])
            ->join(['n' => 'note'], 'n.id = nt.note_id', [])
            ->where(['n.id' => $id]);

        $tagResult = $this->sql->prepareStatementForSqlObject($tagSelect)->execute();
        $tagResultSet = new ResultSet();
        $tagResultSet->initialize($tagResult);

        foreach($tagResultSet->toArray() as $tag) {
            $note['tags'][] = $tag;
        }

        $note = $this->noteHydrator->hydrate($note, new Note());

        return $note;
    }
}
