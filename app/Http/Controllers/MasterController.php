<?php

namespace App\Http\Controllers;

use App\Paciente;
use App\Consulta;
use App\Profissional;
use Illuminate\Http\Request;

use App\Http\Requests;

class MasterController extends Controller
{
    private $tipo_consulta;
    public function __construct()
    {
        $this->idprofissional_criador = 1;
        $this->tipo_consulta = array('Atendimento','Cirurgia','Emergência','Retorno');
    }
    public function home()
    {
        $this->tipo_consulta = array('Atendimento','Cirurgia','Emergência','Retorno');
        $Page = (object)['Targets'=>'Inteligência','Target'=>'Inteligência','Titulo'=> 'Inteligência'];
        return view('pages.master.index')
            ->with('Page', $Page);
    }

    public function agenda()
    {
        $Page = (object)[
            'Targets'       => 'Agendamento',
            'Target'        => 'Agendamento',
            'Consultas'     => Consulta::all(),
            'Pacientes'     => Paciente::all(),
            'Profissionais' => Profissional::all(),
            'TipoConsultas' => $this->tipo_consulta,
            'Titulo'        => 'Agendamento'];
        return view('pages.master.agenda')
            ->with('Page', $Page);
    }
    public function recebimentos()
    {
        $Page = (object)['Targets'=>'Recebimentos','Target'=>'Recebimentos','Titulo'=> 'Recebimentos'];
        return view('pages.master.recebimentos')
            ->with('Page', $Page);
    }

    public function recibos()
    {
        $Page = (object)['Targets'=>'Recibos','Target'=>'Recibos','Titulo'=> 'Recibos'];
        return view('pages.master.recibos')
            ->with('Page', $Page);
    }

    public function editar_perfil()
    {
        $Page = (object)['Targets'=>'Editar','Target'=>'Editar','Titulo'=> 'Editar'];
        return view('pages.master.editar_perfil')
            ->with('Page', $Page);
    }

    public function clinica()
    {
        $Page = (object)['Targets'=>'Clinica','Target'=>'Clinica','Titulo'=> 'Clinica'];
        return view('pages.master.clinica')
            ->with('Page', $Page);
    }

    public function login()
    {
        $Page = (object)['Targets'=>'Login','Target'=>'Login','Titulo'=> 'Login'];
        return view('pages.master.login')
            ->with('Page', $Page);
    }
}
