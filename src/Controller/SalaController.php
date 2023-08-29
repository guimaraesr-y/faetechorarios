<?php

namespace App\Controller;

use App\Dao\SalaDao;

class SalaController extends Controller
{

    public function show()
    {
        return self::render('sala.twig', [
            '_GET' => $_GET,
            'salas' => SalaDao::getAll()
        ]);
    }

    public function store($data)
    {
        SalaDao::store($data);
        header('Location: /salas');
    }

    public function delete($data)
    {
        SalaDao::delete($data['id']);
        header('Location: /salas');
    }

}