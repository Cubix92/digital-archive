<?php

namespace Application\Controller\Api;

use Application\Model\Note;
use Application\Model\NoteCommand;
use Application\Model\NoteRepository;
use Application\Model\Tag;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NoteController extends AbstractRestfulController
{
    protected $noteRepository;

    protected $noteCommand;

    public function __construct(NoteRepository $noteRepository, NoteCommand $noteCommand)
    {
        $this->noteRepository = $noteRepository;
        $this->noteCommand = $noteCommand;
    }

    public function get($id):JsonModel
    {
        try {
            /** @var Note $note */
            $note = $this->noteRepository->findById($id);
        } catch(\UnexpectedValueException $e) {
            return new JsonModel([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        /** @var Tag $tag */
        $tags = [];
        foreach((array)$note->getTags() as $tag) {
            $tags[] = [
                'id' => $tag->getId(),
                'name' => $tag->getName()
            ];
        }

        $data = [
            'id' => $note->getId(),
            'category' => [
                'id' => $note->getCategory()->getId(),
                'name' => $note->getCategory()->getName()
            ],
            'tags' => $tags,
            'title' => $note->getTitle(),
            'content' => $note->getContent(),
            'url' => $note->getUrl(),
            'date_published' => $note->getDatePublished()
        ];

        return new JsonModel([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getList():JsonModel
    {
        /**
         * @var Note $note
         */
        $notes = $this->noteRepository->findAll();
        $data = [];

        foreach($notes as $note) {
            /** @var Tag $tag */
            $tags = [];
            foreach((array)$note->getTags() as $tag) {
                $tags[] = [
                    'id' => $tag->getId(),
                    'name' => $tag->getName()
                ];
            }

            $data = [
                'id' => $note->getId(),
                'category' => [
                    'id' => $note->getCategory()->getId(),
                    'name' => $note->getCategory()->getName()
                ],
                'tags' => $tags,
                'title' => $note->getTitle(),
                'content' => $note->getContent(),
                'url' => $note->getUrl(),
                'date_published' => $note->getDatePublished()
            ];
        }

        return new JsonModel([
            'status' => 'success',
            'data' => [
                'id' => $notes->getId(),
            ],
        ]);
    }

    public function create($data)
    {
        parent::create($data);
    }

    public function update($id, $data)
    {
        parent::update($id, $data);
    }

    public function delete($id)
    {
        parent::delete($id);
    }

    protected function populateNote(Note $note):array
    {

    }
}