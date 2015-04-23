<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Form->postLink(
                __('Delete'),
                ['action' => 'delete', $article->slug],
                ['confirm' => __('Êtes-vous sûr de vouloir supprimer cet article?')]
            )
        ?></li>
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
        ?>
    </fieldset>
    <?= $this->Form->button(__('Valider')) ?>
    <?= $this->Form->end() ?>
</div>
