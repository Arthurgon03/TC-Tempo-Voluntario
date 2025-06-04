<form action="" method="POST" enctype="multipart/form-data">
    <input type="file" name="imagem">
    <button type="submit">Enviar</button>
</form>

<?php
if ($_SERVER['REQUEST_METHOD'] === 'POST') {
    if (move_uploaded_file($_FILES['imagem']['tmp_name'], 'uploads/' . $_FILES['imagem']['name'])) {
        echo "Upload realizado com sucesso!";
    } else {
        echo "Erro ao salvar imagem!";
    }
}
?>