<aside class="main-sidebar">

    <section class="sidebar">

        <!-- Sidebar user panel -->
        <div class="user-panel">
            <div class="pull-left image">
                <img src="img/administrador.png" class="img-circle" alt="User Image"/>
            </div>
            <div class="pull-left info">
                <p><?= Yii::$app->user->identity->nome ?></p>
            </div>
        </div>

        <?= dmstr\widgets\Menu::widget([
            'options' => ['class' => 'sidebar-menu'],
            'items' => [
                ['label' => 'Início','icon' => 'fa fa-home', 'url' => ['site/index'],],
                ['label' => 'Administração', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('administrador')],
                [
                    'label' => 'Usuários',
                    'icon' => 'fa fa-users',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('administrador'),
                    'items' => [
                        ['label' => 'Adicionar Usuário', 'icon' => 'fa fa-user-plus', 'url' => ['site/signup'],],
                        ['label' => 'Listar Usuários', 'icon' => 'fa fa-list', 'url' => ['user/index'],],
                    ],
                ],
                ['label' => 'Coordenação PPGI', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('coordenacao')],
                [
                    'label' => 'Seleções PPGI',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('coordenacao'),
                    'items' => [
                        ['label' => 'Criar Edital de Seleção', 'icon' => 'fa fa-file-code-o', 'url' => ['edital/create'],],
                        ['label' => 'Listar Editais de Seleção', 'icon' => 'fa fa-list', 'url' => ['edital/index'],],
                    ],
                ],
                [
                    'label' => 'Gerenciar Defesas',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('coordenacao'),
                    'items' => [
                        ['label' => 'Listar Defesas', 'icon' => 'fa fa-list', 'url' => ['defesa/index'],],
                    ],
                ],				
                ['label' => 'Professor', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('professor')],
                [
                    'label' => 'Afastamento Temporário',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('professor'),
                    'items' => [
                        ['label' => 'Solicitar Afastamento', 'icon' => 'fa fa-file-code-o', 'url' => ['afastamentos/create'],],
                        ['label' => 'Listar Afastamentos', 'icon' => 'fa fa-list', 'url' => ['afastamentos/index'],],
                    ],
                ],
                [
                    'label' => 'Gerenciar Férias',
                    'icon' => 'fa fa-file-code-o',
                    'url' => '#',
                    'visible' => Yii::$app->user->identity->checarAcesso('coordenacao') || Yii::$app->user->identity->checarAcesso('secretaria'),
                    'items' => [
                        ['label' => 'Minhas Férias', 'icon' => 'fa fa-list', 'url' => ['ferias/listar', "ano" => date("Y") ],],
                    ],
                ],
				['label' => 'Acompanhar Orientandos', 'icon' => 'fa fa-file-code-o', 'url' => ['aluno/orientandos'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],
                ['label' => 'Aluno', 'options' => ['class' => 'header'], 'visible' => Yii::$app->user->identity->checarAcesso('aluno')],
                ['label' => 'Aluno Opção', 'icon' => 'fa fa-file-code-o', 'url' => ['site/index'], 'visible' => Yii::$app->user->identity->checarAcesso('professor'),],
            ],
        ]) ?>
    </section>

</aside>