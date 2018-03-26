<?php

namespace Application\Controller\Api;

use Application\Model\Note;
use Application\Model\NoteCommand;
use Application\Model\NoteHydrator;
use Application\Model\NoteRepository;
use Zend\Mvc\Controller\AbstractRestfulController;
use Zend\View\Model\JsonModel;

class NoteController extends AbstractRestfulController
{
    protected $noteRepository;

    protected $noteCommand;

    protected $noteHydrator;

    public function __construct(NoteRepository $noteRepository, NoteCommand $noteCommand, NoteHydrator $noteHydrator)
    {
        $this->noteRepository = $noteRepository;
        $this->noteCommand = $noteCommand;
        $this->noteHydrator = $noteHydrator;
    }

    public function get($id):JsonModel
    {
        try {
            $note = $this->noteRepository->findById($id);
        } catch(\UnexpectedValueException $e) {
            return new JsonModel([
                'status' => 'error',
                'message' => $e->getMessage()
            ]);
        }

        $data = $this->noteHydrator->extract($note);

        return new JsonModel([
            'status' => 'success',
            'data' => $data,
        ]);
    }

    public function getList():JsonModel
    {
        /** @var Note $note */
        $tags = $this->params()->fromQuery('tags', []);
        $data = $this->noteRepository->fetchByTags($tags);

        return new JsonModel([
            'status' => 'success',
            'data' => $data
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
}