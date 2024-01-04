<?php

namespace App\Controller;

use App\Dao\SalaDao;

class SalaController extends Controller
{

    public function show()
    {
        $salaId = $_GET['id'] ?? null;

        return self::render('sala.twig', [
            'salas' => SalaDao::getAll(),
            'salaSelecionada' => $salaId ? SalaDao::getSalaById($salaId) : [],
            '_GET' => $_GET,
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