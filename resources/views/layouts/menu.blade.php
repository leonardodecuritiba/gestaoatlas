<div class="col-md-3 left_col menu_fixed">
    <div class="left_col scroll-view">
{{--        {{Route::getCurrentRoute()->getPath()}}--}}
{{--        @if(strpos(Route::getCurrentRoute()->getPath(), 'are') !== false)--}}
        <div class="navbar nav_title" style="border: 0;">
            <a href="{{ url('/') }}" class="site_title"><i class="fa fa-rocket"></i> <span>Atlas</span> </a>
        </div>
        <div class="clearfix"></div>

        <!-- menu prile quick info -->
        <div class="profile">
            <div class="profile_pic">
                <img src="{{ asset("images/user.png") }}" alt="..." class="img-circle profile_img">
            </div>
            <div class="profile_info">
                <span> Seja bem vindo,</span>
                <h2>{{Auth::user()->colaborador->nome}}</h2>
            </div>
        </div>
        <!-- /menu prile quick info -->

        <div class="clearfix"></div>

        <!-- sidebar menu -->
        <div id="sidebar-menu" class="main_menu_side hidden-print main_menu">
            <div class="menu_section">
                <h3>. . . . . . . . . . . . . . . . . . . . . . . . . . . </h3>
                <h5 class="text-center">{{config('app.version').' ('.config('app.update').')'}} </h5>
                <ul class="nav side-menu">
                    <li><a href="{{ url('/') }}"><i class="fa fa-database"></i>Inteligência </a>
                    </li>
                    @role('admin')
                        <li><a><i class="fa fa-wrench"></i> Ajustes <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a>Pessoas<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a>Clientes<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="{{ route('segmentos.index') }}">Segmentos</a></li>
                                                <li><a href="{{ route('regioes.index') }}">Regiões Franquia / Filial</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a>Colaboradores <span class="label label-danger pull-right">Incompleto</span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="#">Grupos/Permissões</a></li>
                                            </ul>
                                        </li>
                                        <li><a>Fornecedores<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="{{ route('segmentos_fornecedores.index') }}">Segmentos</a></li>
                                            </ul>
                                        </li>
                                    </ul>
                                </li>
                                <li><a>Insumos<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li><a href="{{ route('grupos.index') }}">Grupos</a></li>
                                        <li><a href="{{ route('marcas.index') }}">Marcas</a></li>
                                        <li><a href="{{ route('tabela_precos.index') }}">Tabelas de Preços</a></li>
                                        <li><a href="{{ route('unidades.index') }}">Unidades</a></li>
                                    </ul>
                                </li>
                                <li><a>Financeiro<span class="fa fa-chevron-down"></span></a>
                                    <ul class="nav child_menu">
                                        <li>
                                            <a>Tributação<span class="fa fa-chevron-down"></span></a>
                                            <ul class="nav child_menu">
                                                <li><a href="{{ route('cst.index') }}">CST</a></li>
                                                <li><a href="{{ route('cfop.index') }}">CFOP</a></li>
                                                <li><a href="{{ route('ncm.index') }}">NCM</a></li>
                                                <li><a href="{{ route('natureza_operacao.index') }}">Nat. Operação</a>
                                                </li>
                                            </ul>
                                        </li>
                                        <li><a href="{{ route('formas_pagamentos.index') }}">Formas de Pagamento</a></li>
                                    </ul>
                                </li>
                                {{--<li><a>Atividades<span class="fa fa-chevron-down"></span></a>--}}
                                {{--<ul class="nav child_menu">--}}
                                {{--<li><a href="{{ route('servicos.index') }}">Serviços</a></li>--}}
                                {{--</ul>--}}
                                {{--</li>--}}
                                <li><a href="{{ route('ajustes.index') }}">Configurações</a></li>
                            </ul>
                        </li>
                    @endrole
                    <li><a><i class="fa fa-users"></i> Pessoas <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('clientes.index') }}">Clientes</a></li>
                            @role('admin')
                            <li><a href="{{ route('fornecedores.index') }}">Fornecedores</a></li>
                            <li><a href="{{ route('colaboradores.index') }}">Colaboradores</a></li>
                            @endrole
                        </ul>
                    </li>
                    @role('admin')
                        <li><a><i class="fa fa-cubes"></i> Insumos <span class="fa fa-chevron-down"></span></a>
                            <ul class="nav child_menu">
                                <li><a href="{{ route('pecas.index') }}">Peças/Produtos</a></li>
                                <li><a href="{{ route('kits.index') }}">Kits</a></li>
                                <li><a href="{{ route('servicos.index') }}">Serviços</a></li>
                            </ul>
                        </li>
                    @endrole
                    <li><a><i class="fa fa-cogs"></i> Atividades<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="#"> Orçamentos</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-handshake-o"></i> Ordens de Serviços<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @role('tecnico')
                            <li><a href="{{ route('ordem_servicos.busca') }}"> Nova Ordem de Serviços</a></li>
                            @endrole
                            <li><a href="{{ route('ordem_servicos.index','todas') }}">Todas</a></li>
                            <li><a href="{{ route('ordem_servicos.index','a-faturar') }}">A faturar</a></li>
                            <li><a href="{{ route('ordem_servicos.index','faturadas') }}">Faturadas</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-money"></i> Financeiro <span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li>
                                <a>O.S. Centro de Custo<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('ordem_servicos.index_centro_custo','todas') }}">Todas</a>
                                    </li>
                                    <li><a href="{{ route('ordem_servicos.index_centro_custo','a-faturar') }}">A
                                            faturar</a></li>
                                    <li>
                                        <a href="{{ route('ordem_servicos.index_centro_custo','faturadas') }}">Faturadas</a>
                                    </li>
                                    </li>
                                </ul>
                            </li>
                            <li>
                                <a>Fechamentos<span class="fa fa-chevron-down"></span></a>
                                <ul class="nav child_menu">
                                    <li><a href="{{ route('fechamentos.index','todas') }}">Todas</a>
                                    </li>
                                    <li><a href="{{ route('fechamentos.index','pendentes') }}">Pendentes</a></li>
                                    <li>
                                        <a href="{{ route('fechamentos.index','faturados') }}">Faturados</a>
                                    </li>
                                    </li>
                                </ul>
                            </li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-line-chart"></i> Relatórios<span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            @if(Auth::user()->hasRole(['admin','financeiro']))
                                <li><a href="{{ route('relatorios.ipem') }}"> IPEM</a></li>
                            @endif
                            <li><a href="#">Meu relatório</a></li>
                        </ul>
                    </li>
                    <li><a><i class="fa fa-car"></i> Frotas <span class="label label-danger pull-right">Incompleto</span><span class="fa fa-chevron-down"></span></a>
                        <ul class="nav child_menu">
                            <li><a href="{{ route('frotas.create') }}">Cadastrar Frota</a></li>
                            <li><a href="{{ route('frotas.index') }}">Visualizar Frota</a></li>
                        </ul>
                    </li>
                </ul>
            </div>
        </div>
        <!-- /sidebar menu -->
    </div>
</div>
<!-- top navigation -->
<div class="top_nav">
    <div class="nav_menu">
        <nav class="" role="navigation">
            <div class="nav toggle">
                <a id="menu_toggle"><i class="fa fa-bars"></i></a>
            </div>
            <ul class="nav navbar-nav navbar-right">
                <li class="">
                    <a href="javascript:;" class="user-profile dropdown-toggle" data-toggle="dropdown"
                       aria-expanded="false">
                        <img src="{{ asset("images/user.png") }}" alt="">{{Auth::user()->colaborador->nome}}
                        <span class=" fa fa-angle-down"></span>
                    </a>
                    <ul class="dropdown-menu dropdown-usermenu animated fadeInDown pull-right">
                        <li><a href="{{route('colaboradores.show',Auth::user()->colaborador->idcolaborador)}}"> Perfil</a></li>
                        <li><a href="{{ url('logout') }}"> <span class="badge bg-red pull-right"><i
                                            class="fa fa-sign-out pull-right"></i></span>Sair</a></li>
                    </ul>
                </li>

                @role('admin')
                <?php $clientes_validar = Auth::user()->colaborador->clientes_invalidos(); ?>
                <li role="presentation" class="dropdown">
                    <a href="javascript:;" class="dropdown-toggle info-number" data-toggle="dropdown"
                       aria-expanded="false">
                        <i class="fa fa-exclamation-circle"></i>
                        @if($clientes_validar->count() > 0)
                            <span class="badge bg-green">{{$clientes_validar->count()}}</span>
                        @endif
                    </a>
                    @if($clientes_validar->count() > 0)
                        <ul id="menu1" class="dropdown-menu list-unstyled msg_list" role="menu">
                            @foreach($clientes_validar as $cliente)
                                <?php $tipo_cliente = $cliente->getType(); ?>
                                <li>
                                    <a href="{{route('clientes.show',$cliente->idcliente)}}">
                                        <span class="image"><img src="{{$cliente->getURLFoto()}}" alt="Profile Image"/></span>
                                        <span>
                                                <span>Cliente</span>
                                                <span class="time">criado há {{$cliente->criado_em()}}</span>
                                            </span>
                                        <span class="message">
                                                {{$tipo_cliente->nome_principal}}
                                            </span>
                                    </a>
                                </li>
                            @endforeach
                            {{--<li>--}}
                            {{--<div class="text-center">--}}
                            {{--<a>--}}
                            {{--<strong>Ver mais</strong>--}}
                            {{--<i class="fa fa-angle-right"></i>--}}
                            {{--</a>--}}
                            {{--</div>--}}
                            {{--</li>--}}
                        </ul>
                    @endif
                </li>
                @endrole
            </ul>
        </nav>
    </div>
</div>