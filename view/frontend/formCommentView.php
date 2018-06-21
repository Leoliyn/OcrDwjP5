<?php $title = 'Jean FORTEROCHE Billet simple pour l\'ALASKA '; ?>

<?php ob_start(); ?>
<h1>Jean FORTEROCHE Billet simple pour l\'ALASKA</h1>

<h2>Modification du Commentaire</h2>
<?php
$data = $comment->fetch();
?>
<form action="index.php?action=updateComment&amp;id=<?= htmlspecialchars($data['id']) ?>" method="post">
    <div>
        <input type="hidden" id='id' name="id" value ="<?= htmlspecialchars($data['id']) ?>">
        <input type="hidden" id='post_id' name="post_id" value ="<?= htmlspecialchars($data['post_id']) ?>">
        <label for="author">Auteur</label><br />
        <input type="text" id="author" name="author" value="<?= htmlspecialchars($data['author']) ?>"/>
    </div>
    <div>
        <label for="comment">Commentaire</label><br />
        <textarea id="comment" name="comment"> <?= htmlspecialchars($data['comment']) ?></textarea>
    </div>
    <div>
        <input type="submit" />
    </div>
</form>

<p><a href="index.php">Retour à la liste des billets</a></p>



<?php $content = ob_get_clean(); ?>

<?php require('template.php'); ?>
