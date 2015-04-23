<div class="actions columns large-2 medium-3">
    <h3><?= __('Actions') ?></h3>
    <ul class="side-nav">
        <li><?= $this->Html->link(__('Edit Article'), ['action' => 'edit', $article->slug]) ?> </li>
        <li><?= $this->Form->postLink(__('Delete Article'), ['action' => 'delete', $article->slug], ['confirm' => __('Êtes-vous sûr de vouloir supprimer cet article?')]) ?> </li>
        <li><?= $this->Html->link(__('List Articles'), ['action' => 'index']) ?> </li>
        <li><?= $this->Html->link(__('New Article'), ['action' => 'add']) ?> </li>
    </ul>
</div>
<div class="articles view large-10 medium-9 columns">
    <h2><?= uc_words(h($article->title)) ?></h2>
    <div class="row">
        <div class="large-5 columns strings">
            <h6 class="subheader"><?= __('Text') ?></h6>
            <p><?= h($article->body) ?></p>
        </div>
        <div class="large-2 columns numbers end">
            <h6 class="subheader"><?= __('Slug') ?></h6>
            <p><?= $article->slug ?></p>
        </div>
        <div class="large-2 columns dates end">
            <h6 class="subheader"><?= __('Created') ?></h6>
            <p><?= h($article->created) ?></p>
            <h6 class="subheader"><?= __('Updated') ?></h6>
            <p><?= h($article->updated) ?></p>
        </div>
    </div>
</div>
