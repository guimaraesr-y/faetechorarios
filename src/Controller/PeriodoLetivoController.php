<?php

namespace App\Controller;

use App\Dao\PeriodoLetivoDao;

class PeriodoLetivoController extends Controller
{
    public function index()
    {
        $periodosLetivos = PeriodoLetivoDao::getAll();

        self::render('periodo-letivo.twig', [
            'periodosLetivos' => $periodosLetivos,
            '_GET' => $_GET,
        ]);
    }

    public function store(Array $data)
    {
        if(empty($data['ano']) || empty($data['periodo'])) {
            die('<script>alert("Preencha todos os campos");history.back();</script>');
        }

        if(strlen(strval($data['ano'])) !== 4 || ($data['periodo'] !== '1' && $data['periodo'] !== '2')) {
            die('<script>alert("Ano ou Período inválido");history.back();</script>');
        }

        PeriodoLetivoDao::store($data);
        header('Location: /periodos');
    }
}