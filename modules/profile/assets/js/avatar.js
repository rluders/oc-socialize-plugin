(function (Croppr) {

    var cropInstance = null;

    const fileReader = new FileReader();
    const avatarImage = document.getElementById('rls-avatar-image');
    const avatarInput = document.getElementById('rls-avatar-input');

    document.querySelectorAll('[rls-avatar-changer]').forEach(elm => {
        elm.addEventListener('click', e => {
            avatarInput.value = "";
            avatarInput.click();
        });
    });

    fileReader.onload = e => {
        $('#rls-avatar-modal').modal('show');

        if (cropInstance) {
            cropInstance.destroy();
        }

        avatarImage.src = e.target.result;

        cropInstance = new Croppr('#rls-avatar-image', {
            aspectRatio: 1,
            startSize: [80, 80, '%'],
            onCropEnd: value => {
                console.log(value);
            }
        });
    };

    avatarInput.addEventListener('change', e => {
        if (e.target.files.length > 0) {
            const f = e.target.files[0];
            fileReader.readAsDataURL(f);
        }
    });

}) (Croppr);