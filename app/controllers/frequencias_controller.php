<?php
class FrequenciasController extends AppController {

	//var $scaffold;

	var $layout = 'tricolor_layout';
	var $pageTitle = 'estudantes';
	var $paginate;

	var $dados;

	//var $helpers = array('questionario');
	var $uses = array('Frequencia', 'Estudante', 'Turma');

	var $components = array('Excel', 'Fpdf');

	function index()
	{
		$this->set('content_title', 'Módulo Estudante'); 
	}

	function inserir()
	{
		$this->set('content_title', 'Inserir Frequência');
		if(!empty($this->data))
		{
			//verifica se esta data ja possui freguências
			if(!$this->Frequencia->possuiRegistro($this->data))
			{
				//pode inserir as frequências
				$ano_letivo = $this->data['Frequencia']['data_frequencia']['year'];
				$unidade = $this->data['Frequencia']['unidade_id'];

				//obter a quantidade de turmas
				$turmas = array();
				$count = 0;
				$num_estudantes = 0;

				//obter os estudantes de cada turma
				$estudantes = array();

				$turmas_unidade = $this->Turma->getAllTurmasPorAnoLetivoUnidade($ano_letivo, $unidade);

				//verifica se há turmas.
				if(count($turmas_unidade) > 0)
				{
					foreach($turmas_unidade as $turma)
					{
						$turmas[] = $turma;

						//obter os estudantes desta turma;
						$estudantes_turma = $this->Estudante->getAllEstudantesPorTurma($turma['Turma']['id']);
						$estudantes[$turma['Turma']['id']] = array();

						if($estudantes_turma)
							foreach($estudantes_turma as $estudante)
							{
								$estudantes[$turma['Turma']['id']][] = $estudante;
								$num_estudantes++;
							}

						$count++;
					}
					//passando as variáveis para a view

					if($num_estudantes == 0)
						$this->Session->setFlash('Não há estudantes neste período');

					$this->set('turmas', $turmas);
					$this->set('estudantes', $estudantes);
					$this->set('count', $count);
					$this->set('data', $this->data['Frequencia']['data_frequencia']);
					$this->render('frequencia');
				}
				else
				{   
                                        // Unidades
                                        $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                                        $this->set('unidades', $array_unidade);

					//não há turmas.
					$this->Session->setFlash('Não há turmas registradas neste período');
					$this->set('metodo_destino', 'inserir');
					$this->render('data');
				}
			}else{
                                // Unidades
                                $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                                $this->set('unidades', $array_unidade);

				//a data informada ja possui registros. deve ser informada outra ou editar
				$this->Session->setFlash('Esta data ja possui frequências. Por favor digite outra ou vá em editar frequência para que possa alterá-las.');
				$this->set('metodo_destino', 'inserir');
				$this->render('data');
			}
		}
		else
		{
                        // Unidades
                        $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                        $this->set('unidades', $array_unidade);
			//obter processo seletivo.
			$this->set('metodo_destino', 'inserir');
			$this->render('data');
		}
	}

	/*
	 * adicionar no banco as frequncias
	 */
	function inserir_direto()
	{
		$this->set('content_title', 'Inserir Frequência');
		$erro = 0;
		//print_r($this->data);

		//obter os valores comuns para que seja adicionado em todas as frequências
		$num_estudantes = $this->data['Frequencia']['num_estudantes'];
		$data = array();
		$data['day'] = $this->data['Frequencia']['day'];
		$data['month'] = $this->data['Frequencia']['month'];
		$data['year'] = $this->data['Frequencia']['year'];

		//para cada estudante, gravar sua presença
		$freq = array();
		$freq['Frequencia']['data_frequencia']['day'] = $data['day'];
		$freq['Frequencia']['data_frequencia']['month'] = $data['month'];
		$freq['Frequencia']['data_frequencia']['year'] = $data['year'];

		for($i = 0; $i < $num_estudantes; $i++)
		{
			//definindo os dados de frequencia na variavel $freq
			$freq['Frequencia']['estudante_id'] = $this->data['Frequencia']['estudante_id'.$i];
			$freq['Frequencia']['presente'] = $this->data['Frequencia']['presente'.$i];

			$this->Frequencia = new $this->Frequencia();
			if(!$this->Frequencia->save($freq))
			{
				$erro++;
			}
		}

		$this->Session->setFlash('Frequências salvas.');
		$this->redirect('/estudantes/index');

	}

	function alterar()
	{
		$this->set('content_title', 'Editar Frequência');

		if(!empty($this->data))
		{
			//verifica se esta data ja possui freguências
			if($this->Frequencia->possuiRegistro($this->data))
			{
				//pode inserir as frequências
				$ano_letivo = $this->data['Frequencia']['data_frequencia']['year'];
				$day = $this->data['Frequencia']['data_frequencia']['day'];
				$month = $this->data['Frequencia']['data_frequencia']['month'];
				$unidade = $this->data['Frequencia']['unidade_id'];

				//obter a quantidade de turmas
				$turmas = array();
				$count = 0;

				//obter os estudantes de cada turma
				$estudantes = array();
				$frequencias = array();

				$turmas_unidade = $this->Turma->getAllTurmasPorAnoLetivoUnidade($ano_letivo, $unidade);

				foreach($turmas_unidade as $turma)
				{
					$turmas[] = $turma;

					//obter os estudantes desta turma;
					$estudantes_turma = $this->Estudante->getAllEstudantesPorTurma($turma['Turma']['id']);
					$estudantes[$turma['Turma']['id']] = array();
					$frequencias[$turma['Turma']['id']] = array();

					if($estudantes_turma)
						foreach($estudantes_turma as $estudante)
						{
							$estudantes[$turma['Turma']['id']][] = $estudante;

							//obter a frequencia no dia especificado
							$freq = $this->Frequencia->obterFrequenciaEstudanteDataSimples($estudante['Estudante']['estudante_id'], $day, $month, $ano_letivo);

							$frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] = $freq;
						}

					$count++;
				}
				//passando as variáveis para a view

				$this->set('turmas', $turmas);
				$this->set('estudantes', $estudantes);
				$this->set('frequencias', $frequencias);
				$this->set('count', $count);
				$this->set('data', $this->data['Frequencia']['data_frequencia']);
				$this->render('frequencia_alterar');

			}else{
				//ainda não há frequencia na data informada, mandar para o inserir
				$this->Session->setFlash('Esta data ja possui frequências. Você redirecionado para a página de inserir frequência.');
				$this->redirect('/frequencias/inserir');
			}
		}
		else
		{
                        // Unidades
                        $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                        $this->set('unidades', $array_unidade);

			//obter processo seletivo.
			$this->set('metodo_destino', 'alterar');
			$this->render('data');
		}
	}

	function alterar_direto()
	{
		$this->set('content_title', 'Editar Frequência');

		$erro = 0;
		//print_r($this->data);

		//obter os valores comuns para que seja adicionado em todas as frequências
		$num_estudantes = $this->data['Frequencia']['num_estudantes'];
		$data = array();
		$data['day'] = $this->data['Frequencia']['day'];
		$data['month'] = $this->data['Frequencia']['month'];
		$data['year'] = $this->data['Frequencia']['year'];

		//para cada estudante, gravar sua presença
		$freq = array();

		for($i = 0; $i < $num_estudantes; $i++)
		{
			//definindo os dados de frequencia na variavel $freq
			$freq['Frequencia']['estudante_id'] = $this->data['Frequencia']['estudante_id'.$i];
			$freq['Frequencia']['frequencia_id'] = $this->data['Frequencia']['frequencia_id'.$i];
			$freq['Frequencia']['presente'] = $this->data['Frequencia']['presente'.$i];
			$freq['Frequencia']['observacao'] = $this->data['Frequencia']['observacao'.$i];

			$this->Frequencia = new $this->Frequencia();
			if(!$this->Frequencia->save($freq))
			{
				$erro++;
			}
		}

		$this->Session->setFlash('Frequências alteradas.');
		$this->redirect('/estudantes/index');

	}

	function visualizar()
	{
		$this->set('content_title', 'Visualizar Frequência');

		if(!empty($this->data))
		{
			//print_r($this->data);

			$inicio = $this->data['inicio'];
			$fim = $this->data['fim'];
			$unidade = $this->data['Frequencia']['unidade_id'];

			//verificar se os anos são iguais, pois a turma deve ser a mesma
			if($inicio['year'] == $fim['year'])
			{
				//verificar se há frequências registradas no período estabelecido
				if($this->Frequencia->existeFrequenciaPeriodo($inicio, $fim))
				{
					//obter a quantidade de turmas
					$turmas = array();
					$count = 0;

					//obter os estudantes de cada turma
					$estudantes = array();
					$frequencias = array();
					$dias_letivo = array();

					//guardando as datas de inicio e fim 
					$inicio_dia = $this->data['inicio']['day'];
					$inicio_mes = $this->data['inicio']['month'];
					$fim_dia = $this->data['fim']['day'];
					$fim_mes = $this->data['fim']['month'];
					$ano_letivo = $inicio['year'];

					$turmas_unidade = $this->Turma->getAllTurmasPorAnoLetivoUnidade($inicio['year'], $unidade);

					//verificar o número de dias letivos no período
					$dias_letivo = $this->Frequencia->obterDiasLetivos($inicio_dia, $inicio_mes, $fim_dia, $fim_mes, $ano_letivo);
					$num_dias_letivo = count($dias_letivo);

					foreach($turmas_unidade as $turma)
					{
						//obter os estudantes desta turma;
						$estudantes_turma = $this->Estudante->getAllEstudantesPorTurma($turma['Turma']['id']);
						$estudantes[$turma['Turma']['id']] = array();
						$frequencias[$turma['Turma']['id']] = array();

						if($estudantes_turma)
							foreach($estudantes_turma as $estudante)
							{
								$estudantes[$turma['Turma']['id']][] = $estudante;

								//obter a frequencia no dia especificado
								$freq = $this->Frequencia->obterFrequenciaEstudanteData($estudante['Estudante']['estudante_id'], $inicio_dia, $inicio_mes, $fim_dia, $fim_mes, $ano_letivo);

								$frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] = $freq;
							}

						$count++;
					}

					$this->set('turmas', $turmas_unidade);
					$this->set('count', $count);
					$this->set('dias_letivos', $dias_letivo);
					$this->set('estudantes', $estudantes);
					$this->set('frequencias', $frequencias);
					$this->set('num_dias_letivo', $num_dias_letivo);
					$this->set('data_inicio', $this->data['inicio']);
					$this->set('data_fim', $this->data['fim']);

				}
				else
				{
                                        // Unidades
                                        $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                                        $this->set('unidades', $array_unidade);
					$this->Session->setFlash('Não há frequências no período estabelicido. Porfavor inserir um período que há registros');
					$this->set('metodo_destino', 'visualizar');
					$this->render('periodo');
				}
			}
			else 
			{
                                // Unidades
                                $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                                $this->set('unidades', $array_unidade);

				$this->Session->setFlash('O ano no intervalo do período não são iguais. Insira um período válido');
				$this->set('metodo_destino', 'visualizar');
				$this->render('periodo');
			}
		}
		else
		{
                        // Unidades
                        $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                        $this->set('unidades', $array_unidade);

			//obter o período
			$this->set('metodo_destino', 'visualizar');
			$this->render('periodo');
		}
	}

	function relatorio($tipo_relatorio = null)
	{
		$this->set('content_title', 'Relatórios');
		//exibe uma lista para a escolha o reletório desejado.
		if($tipo_relatorio != null){
                        // Unidades
                        $array_unidade = $this->Estudante->Candidato->Unidade->getSelectForm();
                        $this->set('unidades', $array_unidade);

			$this->set('metodo_destino', $tipo_relatorio);
			$this->render('select_ano');
		}
	}

	//gera o relatório das frequências gerais dos estudantes num determinado
	function rel_freq_geral()
	{
		$this->set('content_title', 'Relatórios');

		$inicio = $this->data['inicio'];
		$unidade = $this->data['Frequencia']['unidade_id'];

		//verificar se há frequências registradas no período estabelecido
		if($this->Frequencia->existeFrequenciaAno($inicio['year']))
		{			
			//obter a quantidade de turmas
			$turmas = array();
			$count = 0;

			//obter os estudantes de cada turma
			$estudantes = array();
			$frequencias = array();
			$dias_letivo = array();

			//guardando as datas de inicio e fim 
			$ano_letivo = $inicio['year'];

			$turmas_unidade = $this->Turma->getAllTurmasPorAnoLetivoUnidade($ano_letivo, $unidade);

			//verificar o número de dias letivos no período
			$dias_letivo = $this->Frequencia->obterDiasLetivos('01', '01', '31', '12', $ano_letivo);
			$num_dias_letivo = count($dias_letivo);

			foreach($turmas_unidade as $turma)
			{
				$turmas[] = $turma;

				//obter os estudantes desta turma;
				$estudantes_turma = $this->Estudante->getAllEstudantesPorTurma($turma['Turma']['id']);
				$estudantes[$turma['Turma']['id']] = array();
				$frequencias[$turma['Turma']['id']] = array();

				if($estudantes_turma)
					foreach($estudantes_turma as $estudante)
					{
						$estudantes[$turma['Turma']['id']][] = $estudante;

						//obter a frequencia no dia especificado
						$freq = $this->Frequencia->obterFrequenciaEstudanteData($estudante['Estudante']['estudante_id'], '01', '01', '31', '12', $ano_letivo);

						$frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] = $freq;
					}

				$count++;
			}

			//$this->set('turmas', $turmas_unidade);
			//$this->set('count', $count);
			//$this->set('dias_letivos', $dias_letivo);
			//$this->set('estudantes', $estudantes);
			//$this->set('frequencias', $frequencias);
			//$this->set('num_dias_letivo', $num_dias_letivo);
			//$this->set('data_inicio', $this->data['inicio']);
			//$this->set('data_fim', $this->data['fim']);

			//iniciando o componente do Excel
			$this->Excel->iniciando('relatorio_frequencia_'.$ano_letivo.'_'.$unidade.'.xls');
   			$myArr=array('Ano Letivo: ', $ano_letivo);
   			$this->Excel->writeLine($myArr);
   			$myArr=array(' ');
   			$this->Excel->writeLine($myArr);

			foreach ($turmas_unidade as $turma)
			{
				$myArr=array('Turma: ', $turma['Turma']['nome']);
				$this->Excel->writeLine($myArr);
				$myArr=array(' ');
   				$this->Excel->writeLine($myArr);

   				//criar a tabela
   				$myArr=array();
   				$myArr[] = 'Ano';
   				$myArr[] = 'Numero de inscricao';
   				$myArr[] = 'Nome';
   				$myArr[] = 'Freq(%)';
				foreach($dias_letivo as $dia){
		        	$myArr[] = $dia[0]['data'].'';
		        }
		        $this->Excel->writeLine($myArr);

		        $myArr=array();

		        if (is_array($estudantes[$turma['Turma']['id']]))
		        {
		        	foreach ($estudantes[$turma['Turma']['id']] as $estudante)
		        	{
		        		$myArr=array();
		        		$myArr[] = $estudante['Candidato']['ano'].'';
		        		$myArr[] = $estudante['Candidato']['numero_inscricao'].'';
		        		$myArr[] = $estudante['Candidato']['nome'].'';

		        		//fazer a contagem das frequências para calcular a %
			        	$dias_totais = 0;
			        	$presencas = 0;
			        	foreach ($frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] as $frequencia)
			        	{
			        		if($frequencia['frequencia']['presente'] == 'Não'){
			        			$dias_totais++;
			        		} else if($frequencia['frequencia']['presente'] == 'Sim'){
			        			$presencas++;
			        			$dias_totais++;
			        		} else if($frequencia['frequencia']['presente'] == 'Justificado'){
			        			$presencas++;
			        			$dias_totais++;
			        		}
			        	}
                                        if($dias_totais == 0) $dias_totais = 1;
			        	$freq = $presencas*100/$dias_totais;
			        	$freq = round($freq * 100)/100;
			        	$myArr[] = $freq.'';

			        	foreach($dias_letivo as $dia){
							//exibir o status da frequência
					        //buscar pela presença no dia especificado
					        foreach ($frequencias[$turma['Turma']['id']][$estudante['Estudante']['estudante_id']] as $frequencia)
					        {
						        if($dia[0]['data'] == $frequencia[0]['data']){
						        	if($frequencia['frequencia']['presente'] == 'Sim'){
						        		$myArr[] = 'Sim';
						        	} else if($frequencia['frequencia']['presente'] == 'Não'){
						        		$myArr[] = 'Não';
						        	} else {
						        		//justificado
						        		$myArr[] = 'Justificado';
						        	}					        		
					        	}
					        }
				        }
				        $this->Excel->writeLine($myArr);
				        $myArr=array('');
				        $this->Excel->writeLine($myArr);
		        	}
		        }
		        $this->Excel->writeLine($myArr);
		        $myArr=array('');
		        $this->Excel->writeLine($myArr);
			}

			//finalizar o Excel
			$this->Excel->fechando();
		}
		else
		{
			$this->Session->setFlash('Não há frequências no período estabelicido. Por favor inserir um período que há registros');
			$this->redirect('/frequencias/relatorio');
		}
	}
}
?>
