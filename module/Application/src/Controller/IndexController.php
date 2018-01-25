<?php

namespace Application\Controller;

use Application\Model\Note;
use Application\Model\NoteHydrator;
use Application\Model\NoteRepository;
use Zend\Json\Json;
use Zend\Mvc\Controller\AbstractActionController;
use Zend\View\Model\ViewModel;

class IndexController extends AbstractActionController
{
    public $noteRepository;

    public $noteHydrator;

    public function __construct(NoteRepository $noteRepository, NoteHydrator $noteHydrator)
    {
        $this->noteRepository = $noteRepository;
        $this->noteHydrator = $noteHydrator;
    }

    public function indexAction()
    {
        $data = [];
        $notes = $this->noteRepository->findAll();

        /** @var Note $note */
        foreach($notes as $note) {
            $data[] = $this->noteHydrator->extract($note);
        }

        return new ViewModel([
            'data' => Json::encode($data)
        ]);
    }
}
