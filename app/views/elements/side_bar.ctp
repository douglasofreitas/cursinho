<div id="sidebar">

    <?php if($login_group != null): ?>

        <?php if ($modulo == 'home'): ?>
            <ul>
                <li>
                    <h2>Home</h2>
                    <ul>
                        <li><a href="http://www.cursinho.ufscar.br/">Home</a></li>
                        <li><a href="http://www.cursinho.ufscar.br/quem-somos/">O Cursinho</a></li>
                    </ul>
                </li>
            </ul>

        <?php else: ?>

            <?php if ($modulo == 'candidatos'): ?>

                <?php if ($login_group == 'estudante'): ?>

                    <ul>
                        <li>
                            <h2>Consulta</h2>
                            <ul>
                                <li><?php echo $html->link('Inscrição', '/candidatos') ?></li>
                            </ul>
                        </li>
                    </ul>

                <?php else: ?>

                    <ul>
                        <li>
                            <h2>Consulta</h2>
                            <ul>
                                <li><?php echo $html->link('Listar Candidatos', '/candidatos/listar_todos') ?></li>
                                <li><?php echo $html->link('Filtro', '/candidatos/filtrar/formulario') ?></li>
                            </ul>
                        </li>

                        <li>
                            <h2>Inscrição</h2>
                            <ul>
                                <li><?php echo $html->link('Cadastrar', '/candidatos/inserir') ?></li>
                                <li><?php echo $html->link('Editar', '/candidatos/editar') ?></a></li>
                                <li><?php echo $html->link('Definir turmas', '/candidatos/definir_turmas_inicio') ?></a></li>
                            </ul>
                        </li>

                        <li>
                            <h2>Questionário</h2>
                            <ul>
                                <li><?php echo $html->link('Preencher', '/candidatos/preencher_questionario') ?></li>
                                <li><?php echo $html->link('Editar', '/candidatos/preencher_questionario') ?></li>
                                <li><?php echo $html->link('Gráfico pontuação', '/candidatos/visualizar_grafico_socio_economico') ?></li>
                                <li><?php echo $html->link('Fase Eliminatória', '/criterios_da_fase_eliminatorias/iniciar') ?></li>
                            </ul>
                        </li>
                        <li>
                            <h2>Prova</h2>
                            <ul>
                                <li><?php echo $html->link('Cadastrar', '/provas/inserir') ?></li>
                                <li><?php echo $html->link('Listar', '/provas/listar_todas') ?></li>
                                <li><?php echo $html->link('Preencher', '/provas/preencher') ?></li>
                                <li><?php echo $html->link('Relatórios', '/provas/relatorio') ?></li>
                                <li><?php echo $html->link('Fase Classificatória', '/criterios_da_fase_classificatorias/iniciar') ?></li>

                            </ul>
                        </li>
                        <li>
                            <h2>Matrícula</h2>
                            <ul>
                                <li><?php echo $html->link('Matricular', '/candidatos/matricular_selecionar_ano') ?></li>
                                <?php //echo $html->link('Primeira Chamada', '/criterios_da_fase_classificatorias/listar_primeira_chamada') ?>
                                <?php //echo $html->link('Ultima Chamada', '/criterios_da_fase_classificatorias/listar_ultima_chamada') ?>
                                <?php //echo $html->link('Gerar próximas listas', '/criterios_da_fase_classificatorias/iniciar_proxima_chamada'); ?>
                            </ul>
                        </li>

                    </ul>

                <?php endif; ?>

            <?php else: ?>

                <?php if ($modulo == 'estudantes'): ?>
                    <ul>
                        <li>
                            <h2>Estudante</h2>
                            <ul>
                                <li><?php echo $html->link('Consultar', '/estudantes/filtrar/formulario') ?></li>
                                <li><?php echo $html->link('Listar Todos', '/estudantes/listar_todos') ?></li>
                            </ul>
                        </li>
                        <li>
                            <h2>Frequência</h2>
                            <ul>
                                <li><?php echo $html->link('Inserir Frequência', '/frequencias/inserir') ?></li>
                                <li><?php echo $html->link('Editar Frequência', '/frequencias/alterar') ?></li>
                                <li><?php echo $html->link('Banco de Frequência', '/frequencias/visualizar') ?></li>
                                <li><?php echo $html->link('Relatórios', '/frequencias/relatorio') ?></li>
                            </ul>
                        </li>
                        <li>
                            <h2>Boletim</h2>
                            <ul>
                                <li><?php echo $html->link('Inserir Nota', '/boletims/inserir') ?></li>
                                <li><?php echo $html->link('Visualizar Boletim', '/boletims/visualizar') ?></li>
                            </ul>
                        </li>
                        <li>
                            <h2>Mensalidades</h2>
                            <ul>
                                <li><?php echo $html->link('Gerar mensalidades', '/estudantes/gerar_mensalidade_grupo_index', array('title' => '')) ?></li>
								<li><?php echo $html->link('Estudantes atrasados', '/estudantes/atrasados', array('title' => '')) ?></li>
                            </ul>
                        </li>
                        <li>
                            <h2>Advertência</h2>
                            <ul>
                                <li><?php echo $html->link('Inserir', '/advertencias/inserir') ?></li>
                                <li><?php echo $html->link('Estudantes com advertência', '/estudantes/listar_advertencias') ?></li>
                            </ul>
                        </li>
                        <li>
                            <h2>Evasão</h2>
                            <ul>
                                <li><?php echo $html->link('Registrar Evasão', '/evasaos/inserir') ?></li>
                                <li><?php echo $html->link('Estudantes com Evasão', '/estudantes/listar_evasao') ?></li>
                            </ul>
                        </li>
                    </ul>
                <?php else: ?>

                    <?php if ($modulo == 'docente'): ?>
                        <ul>
                            <li>
                                <h2>Item 1</h2>
                                <ul>
                                    <li><?php echo $html->link('item 1.1', '#') ?></li>
                                    <li><?php echo $html->link('item 1.2', '#') ?></li>
                                    <li><?php echo $html->link('item 1.3', '#') ?></li>
                                </ul>
                            </li>
                            <li>
                                <h2>Item 2</h2>
                                <ul>
                                    <li><?php echo $html->link('item 2.1', '#') ?></li>
                                    <li><?php echo $html->link('item 2.2', '#') ?></li>
                                    <li><?php echo $html->link('item 2.3', '#') ?></li>
                                </ul>
                            </li>
                        </ul>
                    <?php else: ?>

                        <?php if ($modulo == 'comissao'): ?>
                            <ul>
                                <li>
                                    <h2>Docente</h2>
                                    <ul>
                                        <li><?php echo $html->link('Cadastrar', '#') ?></li>
                                        <li><?php echo $html->link('Listar Todos', '#') ?></li>
                                    </ul>
                                </li>
                                <li>
                                    <h2>Item 2</h2>
                                    <ul>
                                        <li><?php echo $html->link('item 2.1', '#') ?></li>
                                        <li><?php echo $html->link('item 2.2', '#') ?></li>
                                        <li><?php echo $html->link('item 2.3', '#') ?></li>
                                    </ul>
                                </li>
                            </ul>
                        <?php else: ?>

                            <?php if ($modulo == 'coordenador'): ?>
                                <ul>
                                    <!--
                                    <li>
                                        <h2>Cadastro</h2>
                                        <ul>

                                            <li><?php //echo $html->link('Efetivar docente', '#') ?></li>
                                            <li><?php //echo $html->link('Efetivar comissão', '#') ?></li>
                                        </ul>
                                    </li>
                                    -->
                                    <li>
                                        <h2>Estudantes</h2>
                                        <ul>
                                            <li><?php echo $html->link('Listas Athenas/RU', '/coordenadors/lista_athenas_ru') ?></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <h2>Pagamentos</h2>
                                        <ul>
                                            <li><?php echo $html->link('Faturas', '/faturas/index/candidato') ?></li>
                                            <li><?php echo $html->link('Mensalidade', '/faturas/index/estudante') ?></li>
                                            <li><?php echo $html->link('Boletos', '/faturas/arquivo_retorno') ?></li>
                                        </ul>
                                    </li>
                                    <li>
                                        <h2>Configurações</h2>
                                        <ul>
                                            <li><?php echo $html->link('Processo Seletivo', '/processo_seletivos/index') ?></li>
                                            <li><?php echo $html->link('Turmas', '/turmas/index') ?></li>
                                            <li><?php echo $html->link('Unidades', '/unidades/index') ?></li>
                                        </ul>
                                    </li>

                                            <?php //echo $html->link('Cadastrar', '/coordenadors/lista_athenas_ru') ?>
                                </ul>
                            <?php else: ?>

                                <?php if ($modulo == 'ajuda'): ?>
                                    <ul>
                                        <li>
                                            <h2>Ajuda</h2>
                                            <ul>
                                                <li><?php echo $html->link('Processo Seletivo', '/coordenadors/processo_seletivo') ?></li>
                                                <li><?php echo $html->link('Cadastro de salas de aula', '/salas/inserir') ?></li>
                                                <li><?php echo $html->link('Listar salas de aula', '/salas/visualizar') ?></li>
                                            </ul>
                                        </li>
                                    </ul>
                                <?php endif; ?>

                            <?php endif; ?>
                        <?php endif; ?>
                    <?php endif; ?>
                <?php endif; ?>
            <?php endif; ?>
        <?php endif; ?>

    <?php else: ?>

        <ul>
            <li>
                <h2>Inscrição</h2>
                <ul>
                    <li><?php echo $html->link('Fazer inscrição', '/candidatos/inscricao') ?></li>
                </ul>
            </li>
            <li>
                <h2>Home</h2>
                <ul>
                    <li><a href="http://www.cursinho.ufscar.br/">Home</a></li>
                    <li><a href="http://www.cursinho.ufscar.br/quem-somos/">O Cursinho</a></li>
                </ul>
            </li>
        </ul>

    <?php endif; ?>

</div>