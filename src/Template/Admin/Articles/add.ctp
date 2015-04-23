<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('List Articles'), ['action' => 'index']) ?></li>
    </ul>
</div>
<div class="articles form large-10 medium-9 columns">
    <?= $this->Form->create($article); ?>
    <fieldset>
        <legend><?= __('Edit Article') ?></legend>
        <?php
        echo $this->Form->input('title', array(
            'label' => 'Title'
        ));
        echo $this->Form->input('body', array(
            'label' => 'Body'
        ));
        echo $this->Form->input('user_id', array(
            'type' => 'hidden',
            'value' => $this->request->session()->read('Auth.User.id')
        ));
        ?>
    </fieldset>
    <?= $this->Form->button(__('Valider')) ?>
    <?= $this->Form->end() ?>
</div>
