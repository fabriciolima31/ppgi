<?php

use yii\helpers\Html;
use yii\grid\GridView;
use yii\widgets\Breadcrumbs;


/* @var $this yii\web\View */
/* @var $searchModel app\models\CandidatosSearch */
/* @var $dataProvider yii\data\ActiveDataProvider */

$this->title = 'Candidatos com inscrição finalizada';

$this->params['breadcrumbs'][] = ['label' => 'Editais', 'url' => ['edital/index']];
$this->params['breadcrumbs'][] = ['label' => 'Número: '.Yii::$app->request->get('id'), 
    'url' => ['edital/view','id' => Yii::$app->request->get('id') ]];
$this->params['breadcrumbs'][] = $this->title;

/*
echo   Breadcrumbs::widget([
      'homeLink' => [ 
                      'label' => Yii::t('yii', 'Número: '.Yii::$app->request->get('id')),
                      'url' => 'index.php?r=edital/view&id='.Yii::$app->request->get('id'),
                 ],
      'links' => isset($this->params['breadcrumbs']) ? $this->params['breadcrumbs'] : [],
   ]);
*/

?>
<div class="candidato-index">

<script>
function goBack() {
    window.history.back();
}
</script>

<?= Html::a('Voltar', ['edital/view', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-warning']) ?>

<?= Html::a(' <span class="glyphicon glyphicon-download"></span> Baixar Documentação ', ['candidatos/downloadscompletos', 'id' => Yii::$app->request->get('id')], ['class' => 'btn btn-success']) ?>


    <?= GridView::widget([
        'dataProvider' => $dataProvider,
//        'filterModel' => $searchModel,
            'rowOptions'=> function($model){
                    if($model->cartas_respondidas < 2 && $model->carta_recomendacao == 1){
                        return ['class' => 'warning'];
                    }
                    else if($model->resultado === 1) {
                        return ['class' => 'success'];
                    }
                    else if($model->resultado === 0) {
                        return ['class' => 'danger'];
                    }
                    else{
                        return ['class' => 'info'];
                    }
            },
        'columns' => [
            ['class' => 'yii\grid\SerialColumn'],

            [   'label' => 'Nº de Inscrição',
                'attribute' => 'id',
                'value' => function ($model) {
                     return $model->idEdital.'-'.$model->id;
                },
            ],
            //'senha',
            //'inicio',
            //'fim',
            //'passoatual',
             'nome',
            // 'endereco',
            // 'bairro',
            // 'cidade',
            // 'uf',
            // 'cep',
            // 'email:email',
             ['attribute' => 'qtd_cartas',
              'label' => 'Cartas Emitidas',
              'value' => function ($model){
                       return $model->qtd_cartas;
              }
             ],
             ['attribute' => 'cartas_respondidas',
              'label' => 'Cartas Respondidas',
              'value' => function ($model){
                       return $model->cartas_respondidas;
              }
             ],
            // 'datanascimento',
            // 'nacionalidade',
            // 'pais',
            // 'estadocivil',
            // 'rg',
            // 'orgaoexpedidor',
            // 'dataexpedicao',
            // 'passaporte',
            // 'cpf',
            // 'sexo',
            // 'telresidencial',
            // 'telcomercial',
            // 'telcelular',
            // 'nomepai',
            // 'nomemae',

            [   'label' => 'Curso Desejado',
                'attribute' => 'cursodesejado',
                'value' => function ($model) {
                     return $model->cursodesejado == 0 ? 'Mestrado' : 'Doutorado';
                },
            ],
            // 'regime',
            // 'inscricaoposcomp',
            // 'anoposcomp',
            // 'notaposcomp',
            // 'solicitabolsa',
            // 'vinculoemprego',
            // 'empregador',
            // 'cargo',
            // 'vinculoconvenio',
            // 'convenio',

            [   'label' => 'Linha Pesquisa',
                'attribute' => 'nomeLinhaPesquisa',
            ],


            [   'label' => 'Fase',
                'attribute' => 'fase',
                'value' => function ($model) {

                    if($model->resultado === 1){
                        return "Aprovado";
                    }
                    else if($model->resultado === 0){

                        return "Reprovado";
                    }
                    else{
                        return "Não Julgado";
                    }
                },
            ],


            // 'tituloproposta',
            // 'diploma:ntext',
            // 'historico:ntext',
            // 'motivos:ntext',
            // 'proposta:ntext',
            // 'curriculum:ntext',
            // 'cartaempregador:ntext',
            // 'comprovantepagamento:ntext',
            // 'cursograd',
            // 'instituicaograd',
            // 'crgrad',
            // 'egressograd',
            // 'dataformaturagrad',
            // 'cursoesp',
            // 'instituicaoesp',
            // 'egressoesp',
            // 'dataformaturaesp',
            // 'cursopos',
            // 'instituicaopos',
            // 'tipopos',
            // 'mediapos',
            // 'egressopos',
            // 'dataformaturapos',
            // 'periodicosinternacionais',
            // 'periodicosnacionais',
            // 'conferenciasinternacionais',
            // 'conferenciasnacionais',
            // 'instituicaoingles',
            // 'duracaoingles',
            // 'nomeexame',
            // 'dataexame',
            // 'notaexame',
            // 'empresa1',
            // 'empresa2',
            // 'empresa3',
            // 'cargo1',
            // 'cargo2',
            // 'cargo3',
            // 'periodoprofissional1',
            // 'periodoprofissional2',
            // 'periodoprofissional3',
            // 'instituicaoacademica1',
            // 'instituicaoacademica2',
            // 'instituicaoacademica3',
            // 'atividade1',
            // 'atividade2',
            // 'atividade3',
            // 'periodoacademico1',
            // 'periodoacademico2',
            // 'periodoacademico3',
            // 'resultado',
            // 'periodo',

            ['class' => 'yii\grid\ActionColumn',
              'template'=>'{download} {view} {aprovar} {reprovar}',
                'buttons'=>[
                  'download' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-download"></span>', ['candidatos/downloads', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'target' => '_blank','title' => Yii::t('yii', 'Download da Documentação'),
                    ]);                                

                  },
                  'view' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-eye-open"></span>', ['candidatos/view', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Visualizar Detalhes'),
                    ]);                                

                  },
                  'aprovar' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-ok-sign"></span>', ['candidatos/aprovar', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Aprovar Aluno'),
                            'data-confirm' => \Yii::t('yii', 'Você deseja APROVAR este candidato?'),
                    ]);                                

                  },
                  'reprovar' => function ($url, $model) {  

                    return Html::a('<span class="glyphicon glyphicon-remove-sign"></span>', ['candidatos/reprovar', 'id' => $model->id, 'idEdital' => $model->idEdital], [
                            'title' => Yii::t('yii', 'Reprovar Aluno'),
                            'data-confirm' => \Yii::t('yii', 'Você deseja REPROVAR este candidato?'),
                    ]);                                

                  }
              ]                            
            ],
        ],
    ]); ?>
</div>
