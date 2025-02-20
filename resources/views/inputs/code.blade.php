<fieldset class="input-fieldset">
    <legend class="input-legend">{{ $codeInputTitle ?? 'Код из СМС' }}</legend>
    <input class="input-text" type="number" id={{ $id ?? 'code' }} name="code" required>
</fieldset>
