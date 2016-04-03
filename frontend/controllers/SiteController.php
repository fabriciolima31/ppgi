<?php
namespace frontend\controllers;

use Yii;
use app\models\Candidato;
use app\models\Edital;
use PHPExcel;
use frontend\models\LoginForm;
use common\models\LinhaPesquisa;
use frontend\models\PasswordResetRequestForm;
use frontend\models\ResetPasswordForm;
use frontend\models\SignupForm;
use frontend\models\ContactForm;
use yii\base\InvalidParamException;
use yii\web\BadRequestHttpException;
use yii\web\Controller;
use yii\filters\VerbFilter;
use yii\filters\AccessControl;
use yii\helpers\ArrayHelper;

/**
 * Site controller
 */
class SiteController extends Controller
{
    /**
     * @inheritdoc
     */
    public function behaviors()
    {
        return [
            'access' => [
                'class' => AccessControl::className(),
                'only' => ['signup'],
                'rules' => [
                    [
                        'actions' => ['signup'],
                        'allow' => true,
                        'roles' => ['?'],
                    ],
                ],
            ],
            'verbs' => [
                'class' => VerbFilter::className(),
                'actions' => [
                    'logout' => ['post'],
                ],
            ],
        ];
    }

    /**
     * @inheritdoc
     */
    public function actions()
    {
        return [
            'error' => [
                'class' => 'yii\web\ErrorAction',
            ],
            'captcha' => [
                'class' => 'yii\captcha\CaptchaAction',
                'fixedVerifyCode' => YII_ENV_TEST ? 'testme' : null,
            ],
        ];
    }

    /**
     * Displays homepage.
     *
     * @return mixed
     */
    public function actionIndex()
    {

        $this->actionTesteplanilha();


        $this->layout = '@app/views/layouts/main-login.php';
        $model = new Candidato();
        return $this->render('opcoes',['model' => $model,
            ]);
    }
    
    
    public function planilhaCandidatoFormatacao($objPHPExcel){

    //definindo a altura das linhas

    for ($i=1; $i<999; $i++){
        $objPHPExcel->getActiveSheet()->getRowDimension(''.$i.'')->setRowHeight(20);
    }

    // Centralizando o valor nas colunas

        $objPHPExcel->getActiveSheet()->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle( "A1:K999" )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objPHPExcel->getActiveSheet()->getStyle( "B3:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objPHPExcel->getActiveSheet()->getStyle( "B3:K999" )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

    //auto break line
        
        $objPHPExcel->getActiveSheet()
            ->getStyle('A1:K999')
            ->getAlignment()
            ->setWrapText(true);

    // Configurando diferentes larguras para as colunas
    $objPHPExcel->getActiveSheet()->getColumnDimension('A')->setWidth(40);
    $objPHPExcel->getActiveSheet()->getColumnDimension('B')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('C')->setWidth(18);
    $objPHPExcel->getActiveSheet()->getColumnDimension('D')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('E')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('F')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('G')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('H')->setWidth(12);
    $objPHPExcel->getActiveSheet()->getColumnDimension('I')->setWidth(14);
    $objPHPExcel->getActiveSheet()->getColumnDimension('J')->setWidth(15);
    $objPHPExcel->getActiveSheet()->getColumnDimension('K')->setWidth(40);

    }
    
    //método responsável por preencher na planilha os títulos: NOME/INSCRIÇÃO/LINHA/NÍVEL/COMPROVANTE/ ETC.
    
    public function planilhaHeaderCandidato ($objPHPExcel,$arrayColunas,$curso,$intervaloHeader){
    
        // Criamos as colunas
        $objPHPExcel->setActiveSheetIndex(0)
                ->setCellValue($arrayColunas[0], $curso )
                ->setCellValue($arrayColunas[1], "Nome" )
                ->setCellValue($arrayColunas[2], "Inscrição" )
                ->setCellValue($arrayColunas[3], "Linha" )
                ->setCellValue($arrayColunas[4], "Nível" )
                ->setCellValue($arrayColunas[5], "Comprovante" )
                ->setCellValue($arrayColunas[6], "Curriculum" )
                ->setCellValue($arrayColunas[7], "Histórico" )
                ->setCellValue($arrayColunas[8], "Proposta" )
                ->setCellValue($arrayColunas[9], "Cartas \n(2 no mínimo)" )
                ->setCellValue($arrayColunas[10], "Homologado" )
                ->setCellValue($arrayColunas[11], "Observações");

        //mesclando celulas

        $objPHPExcel->getActiveSheet()->mergeCells($intervaloHeader);

        //colocando os títulos em Negrito

        $objPHPExcel->getActiveSheet()->getStyle($intervaloHeader)->getFont()->setBold(true);
        $objPHPExcel->getActiveSheet()->getStyle($arrayColunas[1].":".$arrayColunas[11])->getFont()->setBold(true);

        $objPHPExcel->getActiveSheet()
            ->getStyle($intervaloHeader)
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objPHPExcel->getActiveSheet()->getStyle($intervaloHeader)->getFont()->getColor()->setRGB('FFFFFF');


                
    }

    //método responsável por preencher na planilha dados provenientes do banco: NOME/INSCRIÇÃO/LINHA/NÍVEL

    public function planilhaCandidatoPreencherDados($objPHPExcel,$model_candidato_doutorado,$linhasPesquisas,$arrayCurso,$i,$j){


        for($j=0; $j<count($model_candidato_doutorado); $j++){
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(0, $i+3, $model_candidato_doutorado[$j]->nome);
            
            $objPHPExcel->getActiveSheet()
                ->setCellValueByColumnAndRow(1, $i+3, ($model_candidato_doutorado[$j]->idEdital.'-'.str_pad($model_candidato_doutorado[$j]->posicaoEdital, 3, "0", STR_PAD_LEFT)));
            
            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(2, $i+3, $linhasPesquisas[$model_candidato_doutorado[$j]->idLinhaPesquisa]);

            $objPHPExcel->getActiveSheet()->setCellValueByColumnAndRow(3, $i+3, $arrayCurso[$model_candidato_doutorado[$j]->cursodesejado]);   

            $i++;
        }

        return $i;
    }

    public function planilhaProvas($objWorkSheet,$linhaAtual,$ultimaLinha){

        $objWorkSheet->mergeCells("A1:C1");

        $objWorkSheet->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $objWorkSheet->getStyle("A1:C2")->getFont()->setBold(true);

        for ($k=1; $k<999; $k++){
            $objWorkSheet->getRowDimension(''.$k.'')->setRowHeight(20);
        }

        //definindo altura da linha do header
        $objWorkSheet->getRowDimension(1)->setRowHeight(20);
        $objWorkSheet->getRowDimension(2)->setRowHeight(40);

        $objWorkSheet->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $objWorkSheet->getStyle( "A1:K999" )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $objWorkSheet->getColumnDimension('A')->setWidth(40);
        $objWorkSheet->getColumnDimension('B')->setWidth(15);
        $objWorkSheet->getColumnDimension('C')->setWidth(18);

        $objWorkSheet
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Nome" )
                ->setCellValue("B2", "Inscrição" )
                ->setCellValue("C2", "Nota Final" );


        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $objWorkSheet
                ->setCellValue('A'.($i+3), "='Candidato'!A".($i+3));
        }

        $i = $i+4;

        $objWorkSheet
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Nome" )
                ->setCellValue("B".($i), "Inscrição" )
                ->setCellValue("C".($i), "Nota Final" );

        $objWorkSheet->getStyle("A".($i-1).":C".$i)->getFont()->setBold(true);

        $objWorkSheet->mergeCells("A".($i-1).":C".($i-1));

        //definindo altura da linha do header

        $objWorkSheet->getRowDimension($i-1)->setRowHeight(20);
        $objWorkSheet->getRowDimension($i)->setRowHeight(40);

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $objWorkSheet
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objWorkSheet->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado


        $objWorkSheet
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $objWorkSheet->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');



        //Write cells
        for ($i=$i+1; $i< $ultimaLinha+3; $i++){

            $objWorkSheet
                ->setCellValue('A'.($i), "='Candidato'!A".($i))
                ->setCellValue('B'.($i), "='Candidato'!B".($i));
        }

        // Rename sheet
        $objWorkSheet->setTitle("Provas");

    }

    public function planilhaPropostas($planilhaPropostas,$linhaAtual,$ultimaLinha){

        $planilhaPropostas->mergeCells("A1:E1");


        $planilhaPropostas->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $planilhaPropostas->getStyle("A1:E2")->getFont()->setBold(true);

        for ($k=1; $k<999; $k++){
            $planilhaPropostas->getRowDimension(''.$k.'')->setRowHeight(20);
        }

        //definindo altura da linha do header
        $planilhaPropostas->getRowDimension(1)->setRowHeight(20);
        $planilhaPropostas->getRowDimension(2)->setRowHeight(40);

        $planilhaPropostas->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $planilhaPropostas->getStyle( "A1:K999" )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);


        $planilhaPropostas->getColumnDimension('A')->setWidth(40);
        $planilhaPropostas->getColumnDimension('B')->setWidth(18);
        $planilhaPropostas->getColumnDimension('C')->setWidth(18);
        $planilhaPropostas->getColumnDimension('D')->setWidth(18);
        $planilhaPropostas->getColumnDimension('E')->setWidth(18);

        $planilhaPropostas
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Nome" )
                ->setCellValue("B2", "Avaliador 1" )
                ->setCellValue("C2", "Avaliador 2" )
                ->setCellValue("D2", "Avaliador 3" )
                ->setCellValue("E2", "Média Final" );


        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $planilhaPropostas
                ->setCellValue('A'.($i+3), "='Candidato'!A".($i+3))
                ->setCellValue('E'.($i+3), '=AVERAGE(B'.($i+3).':D'.($i+3).')');
        }

        $i = $i+4;

        $planilhaPropostas
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Nome" )
                ->setCellValue("B".($i), "Avaliador 1" )
                ->setCellValue("C".($i), "Avaliador 2" )
                ->setCellValue("D".($i), "Avaliador 3" )
                ->setCellValue("E".($i), "Média Final" );

        $planilhaPropostas->getStyle("A".($i-1).":E".$i)->getFont()->setBold(true);

        $planilhaPropostas->mergeCells("A".($i-1).":E".($i-1));

        //definindo altura da linha do header

        $planilhaPropostas->getRowDimension($i-1)->setRowHeight(20);
        $planilhaPropostas->getRowDimension($i)->setRowHeight(40);

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $planilhaPropostas
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaPropostas->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado


        $planilhaPropostas
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaPropostas->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');



        //Write cells
        for ($i=$i+1; $i< $ultimaLinha+3; $i++){

            $planilhaPropostas
                ->setCellValue('A'.($i), "='Candidato'!A".($i))
                ->setCellValue('E'.($i), '=AVERAGE(B'.($i).':D'.($i).')');
        }

        $planilhaPropostas->setTitle("Propostas");

    }

    public function planilhaTitulos($planilhaTitulos,$linhaAtual,$ultimaLinha){

        $planilhaTitulos->mergeCells("A1:J1");
        $planilhaTitulos->mergeCells("A2:A3");

        $planilhaTitulos->mergeCells("B2:E2");
        $planilhaTitulos->mergeCells("F2:H2");

        $planilhaTitulos->mergeCells("I2:I3");
        $planilhaTitulos->mergeCells("J2:J3");

        $planilhaTitulos->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        $planilhaTitulos->getStyle("A1:J2")->getFont()->setBold(true);

        for ($k=1; $k<999; $k++){
            $planilhaTitulos->getRowDimension(''.$k.'')->setRowHeight(20);
        }

        $planilhaTitulos->getRowDimension(3)->setRowHeight(40);

        //definindo altura da linha do header
        $planilhaTitulos->getRowDimension(1)->setRowHeight(20);
        $planilhaTitulos->getRowDimension(2)->setRowHeight(20);

        $planilhaTitulos->getStyle( "A1:K999" )->getAlignment()->setHorizontal(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);
        $planilhaTitulos->getStyle( "A1:K999" )->getAlignment()->setVertical(\PHPExcel_Style_Alignment::HORIZONTAL_CENTER);

        //auto break line
        
        $planilhaTitulos
            ->getStyle('A1:K999')
            ->getAlignment()
            ->setWrapText(true);


        $planilhaTitulos->getColumnDimension('A')->setWidth(40);
        $planilhaTitulos->getColumnDimension('B')->setWidth(15);
        $planilhaTitulos->getColumnDimension('C')->setWidth(18);

        $planilhaTitulos
                ->setCellValue("A1", "Mestrado" )
                ->setCellValue("A2", "Nome" )
                ->setCellValue("B2", "Atividades Curriculares e Extracurriculares (30 pontos)" )
                ->setCellValue("F2", "Publicações (70 pontos)" )
                ->setCellValue("B3", "Mestrado" )
                ->setCellValue("C3", "Estágio, Extensão e monitoria" )
                ->setCellValue("D3", "Docência" )
                ->setCellValue("E3", "IC, IT, ID" )
                ->setCellValue("F3", "A" )
                ->setCellValue("G3", "B1 a B2" )
                ->setCellValue("H3", "B3 a B5" )
                ->setCellValue("I2", "Nota" )
                ->setCellValue("J2", "NAC" );


        //Write cells
        for ($i=0; $i< $linhaAtual; $i++){

            $planilhaTitulos
                ->setCellValue('A'.($i+4), "='Candidato'!A".($i+3));
        }

        $i = $i+5;

        $planilhaTitulos->mergeCells("I".($i).":"."I".($i+1));

        $planilhaTitulos
                ->setCellValue("A".($i-1), "Doutorado" )
                ->setCellValue("A".($i), "Nome" )
                ->setCellValue("B".($i), "Atividades Curriculares e Extracurriculares (30 pontos)" )
                ->setCellValue("F".($i), "Publicações (70 pontos)" )
                ->setCellValue("B".($i+1), "Mestrado" )
                ->setCellValue("C".($i+1), "Estágio, Extensão e monitoria" )
                ->setCellValue("D".($i+1), "Docência" )
                ->setCellValue("E".($i+1), "IC, IT, ID" )
                ->setCellValue("F".($i+1), "A" )
                ->setCellValue("G".($i+1), "B1 a B2" )
                ->setCellValue("H".($i+1), "B3 a B5" )
                ->setCellValue("I".($i), "Nota" );

        $planilhaTitulos->getStyle("A".($i-1).":J".$i)->getFont()->setBold(true);

        $planilhaTitulos->mergeCells("A".($i-1).":J".($i-1));

        $planilhaTitulos->mergeCells("A".($i).":A".($i+1));

        $planilhaTitulos->mergeCells("B".($i).":E".($i));

        $planilhaTitulos->mergeCells("F".($i).":H".($i)); 

        //definindo altura da linha do header

        $planilhaTitulos->getRowDimension($i-1)->setRowHeight(20);
        $planilhaTitulos->getRowDimension($i)->setRowHeight(20);
        $planilhaTitulos->getRowDimension($i+1)->setRowHeight(40);

        //definindo a cor de fundo e cor da fonte do título do header: mestrado

        $planilhaTitulos
            ->getStyle("A1:C1")
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaTitulos->getStyle("A1:C1")->getFont()->getColor()->setRGB('FFFFFF');

        //definindo a cor de fundo e cor da fonte do título do header: doutorado



        $planilhaTitulos
            ->getStyle("A".($i-1).":C".($i-1))
            ->getFill()
            ->setFillType(\PHPExcel_Style_Fill::FILL_SOLID)
            ->getStartColor()
            ->setARGB('FF808080');

        $planilhaTitulos->getStyle("A".($i-1).":C".($i-1))->getFont()->getColor()->setRGB('FFFFFF');



        //Write cells
        for ($i; $i< $ultimaLinha+3; $i++){

            $planilhaTitulos
                ->setCellValue('A'.($i+2), "='Candidato'!A".($i));
        }

        // Rename sheet
        $planilhaTitulos->setTitle("Títulos");

    }
    
   
    public function actionTesteplanilha(){

        $arrayCurso = array(1 => "Mestrado", 2 => "Doutorado");
        $arrayColunas = array(
            0 => "A1", 1 => "A2", 2 => "B2", 3 => "C2", 4 => "D2", 
            5 => "E2", 6 => "F2", 7 => "G2", 8 => "H2", 9 => "I2", 
            10 => "J2", 11 => "K2");

        $linhasPesquisas = ArrayHelper::map(LinhaPesquisa::find()->orderBy('sigla')->all(), 'id', 'sigla');

        $model_candidato_mestrado = Candidato::find()->where("cursodesejado = 1 AND passoatual = 4")->orderBy("nome")->all();
        $model_candidato_doutorado = Candidato::find()->where("cursodesejado = 2 AND passoatual = 4")->orderBy("nome")->all();

        //instanciando objeto Excel

        $objPHPExcel = new \PHPExcel();

        //função responsável pela formatação da planilha
        
        $this->planilhaCandidatoFormatacao($objPHPExcel);

        //função responsável pelo Header da planilha

        $intervaloHeader = 'A1:K1';
        $objPHPExcel->getActiveSheet()->getRowDimension("2")->setRowHeight(40);
        $this->planilhaHeaderCandidato($objPHPExcel,$arrayColunas,$arrayCurso[1],$intervaloHeader);

        //parte referente ao mestrado (preenchimento da tabela a partir do banco)

        $i = $this->planilhaCandidatoPreencherDados($objPHPExcel,$model_candidato_mestrado,$linhasPesquisas,$arrayCurso,0,0);

        //fim da parte referente ao mestrado

        //parte referente ao doutorado (preenchimento da tabela a partir do banco)

            $j = $i;

            $objPHPExcel->getActiveSheet()->getRowDimension($j+4)->setRowHeight(40);

            $k = $j+4;
            $l = $j+3;

            $intervaloHeader = 'A'.$l.':K'.$l.'';

            $arrayColunas = array(
                0 => "A".$l, 1 => "A".$k, 2 => "B".$k, 3 => "C".$k, 4 => "D".$k, 
                5 => "E".$k, 6 => "F".$k, 7 => "G".$k, 8 => "H".$k, 9 => "I".$k, 
                10 => "J".$k, 11 => "K".$k);
                
            $this->planilhaHeaderCandidato($objPHPExcel,$arrayColunas,$arrayCurso[2],$intervaloHeader);

            $j= $this->planilhaCandidatoPreencherDados($objPHPExcel,$model_candidato_doutorado,$linhasPesquisas,$arrayCurso,$i+2,$j);

        //fim da parte referente ao doutorado


// Podemos renomear o nome das planilha atual, lembrando que um único arquivo pode ter várias planilhas
        $objPHPExcel->getActiveSheet()->setTitle('Candidato');

        
        //cria planilha de PROVAS
        $planilhaProvas = $objPHPExcel->createSheet(1);
        $this->planilhaProvas($planilhaProvas,$i,$j);


        //cria planilhas Propostas
        $planilhaPropostas = $objPHPExcel->createSheet(2);
        $this->planilhaPropostas($planilhaPropostas,$i,$j);

        //Cria planilhas de Títulos
        $planilhaTitulos = $objPHPExcel->createSheet(3);
        $this->planilhaTitulos($planilhaTitulos,$i,$j);
        
        // Acessamos o 'Writer' para poder salvar o arquivo
        $objWriter = \PHPExcel_IOFactory::createWriter($objPHPExcel, 'Excel5');
        
        // Salva diretamente no output, poderíamos mudar arqui para um nome de arquivo em um diretório ,caso não quisessemos jogar na tela

            header('Content-type: application/vnd.ms-excel');

            header('Content-Disposition: attachment; filename="file.xls"');

            $objWriter->save('php://output');
            $objWriter->save('ARQUIVO.xls');

        
        echo "ok";
        
        
    }


    public function actionCadastroppgi(){
        /*if(Yii::$app->session->get('candidato') !== null)
        $this->redirect(['candidato/passo1']);*/
    
        $this->layout = '@app/views/layouts/main-login.php';
        
        $model = new Candidato();  

        if ($model->load(Yii::$app->request->post())){                

            $model->inicio = date("Y-m-d H:i:s");
            $model->passoatual = 0;
            $model->repetirSenha = $model->senha = Yii::$app->security->generatePasswordHash($model->senha);
            $model->status = 10;

            try{
                if(!$model->save()){
                    $this->mensagens('warning', 'Candidato Já Inscrito', 'Candidato Informado Já se Encontra cadastrado para este edital, Efetue o seu Login.');

                    return $this->redirect(['site/login']);
                }else{
                    //setando o id do candidato via sessão
                        $session = Yii::$app->session;
                        $session->open();
                        $session->set('candidato',$model->id);
                    //fim -> setando id do candidato

                    return $this->redirect(['candidato/passo1']);
                }
            }catch(\Exception $e){ 
                $this->mensagens('danger', 'Erro ao salvar candidato', 'Verifique os campos e tente novamente');
                throw new \yii\web\HttpException(405, 'Erro com relação ao identificador do edital'); 
            }
        }

        $edital = new Edital();
        $edital = $edital->getEditaisDisponiveis();

        return $this->render('/candidato/create0', [
            'model' => $model,
            'edital' => $edital,
        ]);
    }

    public function actionLogin()
    {

        /*Redirecionamento para o formulário caso candidato esteja "logado"*/
        
        /*if(Yii::$app->session->get('candidato') !== null)
            $this->redirect(['candidato/passo1']);*/


        $model = new LoginForm();
        if ($model->load(Yii::$app->request->post()) && $model->login()){

            //setando o id do candidato via sessão
            $session = Yii::$app->session;
            $session->open();
            $session->set('candidato', Yii::$app->user->identity->id);
            //fim -> setando id do candidato
            $this->redirect(['candidato/passo1']);
        }else{

        $edital = new Edital();
        $edital = $edital->getEditaisDisponiveis();
            
            return $this->render('login', [
                'model' => $model,
                'edital' => $edital,

            ]);
        }
    }

    /**
     * Logs out the current user.
     *
     * @return mixed
     */
    public function actionLogout()
    {
        Yii::$app->session->destroy();

        return $this->goHome();
    }

    public function actionSignup()
    {

        $model = new SignupForm();
        if ($model->load(Yii::$app->request->post())) {
            if ($user = $model->signup()) {
                if (Yii::$app->getUser()->login($user)) {
                    return $this->goHome();
                }
            }
        }

        return $this->render('signup', [
            'model' => $model,
        ]);
    }


    public function actionRequestPasswordReset()
    {
        $model = new PasswordResetRequestForm();
        if ($model->load(Yii::$app->request->post()) && $model->validate()) {
            if ($model->sendEmail()) {
                $this->mensagens('success', 'Email Enviado com Sucesso.', 'Verifique sua conta de email');

                return $this->goHome();
            } else {
                $this->mensagens('warning', 'Erro ao Enviar Email', 'Desculpe, o email não pode ser enviado. Verique sua conexão ou contate o administrador');
            }
        }

        return $this->render('requestPasswordResetToken', [
            'model' => $model,
        ]);
    }

    /**
     * Resets password.
     *
     * @param string $token
     * @return mixed
     * @throws BadRequestHttpException
     */
    public function actionResetPassword($token)
    {
        try {
            $model = new ResetPasswordForm($token);
        } catch (InvalidParamException $e) {
            throw new BadRequestHttpException($e->getMessage());
        }

        if ($model->load(Yii::$app->request->post()) && $model->validate() && $model->resetPassword()) {
            Yii::$app->session->setFlash('success', 'New password was saved.');

            return $this->goHome();
        }

        return $this->render('resetPassword', [
            'model' => $model,
        ]);
    }
        /* Envio de mensagens para views
       Tipo: success, danger, warning*/
    protected function mensagens($tipo, $titulo, $mensagem){
        Yii::$app->session->setFlash($tipo, [
            'type' => $tipo,
            'icon' => 'home',
            'duration' => 5000,
            'message' => $mensagem,
            'title' => $titulo,
            'positonY' => 'top',
            'positonX' => 'center',
            'showProgressbar' => true,
        ]);
    }
}
