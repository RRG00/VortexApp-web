<?php

use yii\helpers\Html;
use yii\helpers\Url;

/* @var $this yii\web\View */
/* @var $teamId integer */
/* @var $messages frontend\models\ChatMensagem[] */

$this->title = 'Chat da Equipa';
?>

<div class="chat-container">
    <h1 class="chat-title">Chat da Equipa (<?= Html::encode($teamName) ?> )</h1>

    <div class="chat-messages">
        <?php if (empty($messages)): ?>
            <p class="text-muted">Ainda não existem mensagens neste chat.</p>
        <?php else: ?>
            <?php foreach ($messages as $msg): ?>
                <?php
                $role = $msg['funcao'] ?? 'player';

                if ($role === 'capitao') {
                    $roleLabel = 'Capitao';
                    $roleClass = 'badge-captain';
                } else { 
                    $roleLabel = 'Player';
                    $roleClass = 'badge-player';
                }

                $userModel = \common\models\User::findOne((int)$msg['id_user']); 
                $username  = $userModel ? $userModel->username : ('User #' . $msg['id_user']);
                ?>
                <div class="chat-message">
                    <div class="chat-meta">
                        <span class="chat-user">
                            <?= Html::encode($username) ?>
                            <span class="chat-role-badge <?= $roleClass ?>">
                                <?= Html::encode($roleLabel) ?>
                            </span>
                        </span>
                        <span class="chat-time">
                            <?= Html::encode($msg['created_at']) ?>
                        </span>
                    </div>
                    <div class="chat-text">
                        <?= nl2br(Html::encode($msg['mensagem'])) ?>
                    </div>
                </div>
            <?php endforeach; ?>
        <?php endif; ?>
    </div>
    <div class="chat-form">
        <form method="post" action="<?= Url::to(['chat/team', 'id' => $teamId]) ?>">
            <?= Html::hiddenInput(Yii::$app->request->csrfParam, Yii::$app->request->getCsrfToken()) ?>

            <div class="mb-3">
                <label for="chat-message" class="form-label">Nova mensagem</label>
                <textarea id="chat-message"
                    name="message"
                    class="form-control"
                    rows="3"
                    placeholder="Escreve a tua mensagem..."></textarea>
            </div>
            <button type="submit" class="btn btn-primary">
                Enviar
            </button>
        </form>
    </div>
</div>