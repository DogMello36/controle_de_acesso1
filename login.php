<?php include "config.php";
include(HEADER_TEMPLATE); ?>

<h2>Novo Cliente</h2>

<form action="add.php" method="post" enctype="multipart/form-data">
    <!-- area de campos do form -->
    <hr>
    <div class="row">
        <div class="form-group col-md-7">
            <label for="name">Nome / Razão Social</label>
            <input type="text" class="form-control" id="name" name="customer['name']">
        </div>

        <div class="form-group col-md-3">
            <label for="cpf_cnpj">CNPJ / CPF</label>
            <input type="text" class="form-control" id="cpf_cnpj" name="customer['cpf_cnpj']" maxlength="14">
        </div>

        <div class="form-group col-md-2">
            <label for="birthdate">Data de Nascimento</label>
            <input type="date" class="form-control" id="birthdate" name="customer['birthdate']">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-5">
            <label for="address">Endereço</label>
            <input type="text" class="form-control" id="address" name="customer['address']">
        </div>

        <div class="form-group col-md-3">
            <label for="hood">Bairro</label>
            <input type="text" class="form-control" id="hood" name="customer['hood']">
        </div>

        <div class="form-group col-md-2">
            <label for="zip_code">CEP</label>
            <input type="text" class="form-control" id="zip_code" name="customer['zip_code']" maxlength="8">
        </div>

        <div class="form-group col-md-2">
            <label for="created">Data de Cadastro</label>
            <input type="date" class="form-control" id="created" name="customer['created']" disabled>
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-5">
            <label for="city">Município</label>
            <input type="text" class="form-control" id="city" name="customer['city']">
        </div>

        <div class="form-group col-md-2">
            <label for="phone">Telefone</label>
            <input type="text" class="form-control" id="phone" name="customer['phone']" maxlength="11">
        </div>

        <div class="form-group col-md-2">
            <label for="mobile">Celular</label>
            <input type="text" class="form-control" id="mobile" name="customer['mobile']" maxlength="11">
        </div>

        <div class="form-group col-md-1">
            <label for="state">UF</label>
            <input type="text" class="form-control" id="state" name="customer['state']" maxlength="2">
        </div>

        <div class="form-group col-md-2">
            <label for="ie">Inscrição Estadual</label>
            <input type="text" class="form-control" id="ie" name="customer['ie']" maxlength="15">
        </div>
    </div>

    <div class="row">
        <div class="form-group col-md-4">
            <label for="imagem">Imagem</label>
            <input type="file" class="form-control" id="imagem" name="customer[imagem]"
                accept="image/png, image/jpeg, image/gif, image/webp">
            <small class="rl-hint">JPG, PNG, GIF ou WEBP, até 2MB.</small>

            <div id="imagem-preview-wrapper" class="rl-current-image" style="display: none;">
                <img id="imagem-preview" src="" alt="Prévia da imagem selecionada">
                <span>Prévia da imagem selecionada</span>
            </div>
        </div>
    </div>

    <div id="actions" class="row">
        <div class="col-md-12">
            <button type="submit" class="btn btn-secondary mb-2">
                <i class="fa-solid fa-floppy-disk"></i> Salvar
            </button>
            <a href="index.php" class="btn btn-light">
                <i class="fa-solid fa-rotate-left"></i> Cancelar
            </a>
        </div>
    </div>
</form>

<script>
    document.getElementById('imagem').addEventListener('change', function (event) {
        var wrapper = document.getElementById('imagem-preview-wrapper');
        var preview = document.getElementById('imagem-preview');
        var arquivo = event.target.files[0];

        if (!arquivo) {
            wrapper.style.display = 'none';
            preview.src = '';
            return;
        }

        var leitor = new FileReader();
        leitor.onload = function (e) {
            preview.src = e.target.result;
            wrapper.style.display = 'flex';
        };
        leitor.readAsDataURL(arquivo);
    });
</script>

<?php include "config.php";
include(FOOTER_TEMPLATE); ?>