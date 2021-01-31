const REQUIRE = 'required';
const STRING = 'string';
const CONTENT = 'content';
const URL = 'url';
const EMAIL = 'email';
const INTEGER = 'integer';
const NULLABLE = 'nullable';

export function validate(name, value, params) {
    let pattern;
    let error = '';
    let errors = [];

    if (params.includes(NULLABLE)) {
        return null;
    }

    params.forEach(param => {
        switch (param) {
            case REQUIRE:
                pattern = /^.{6,}$/;
                error = 'Ошибка в поле %name% со значением %value%, минимум 6 символов.';
                break;
            case STRING:
                pattern = /^[a-zA-Zа-яА-Я]+$/;
                error = 'Ошибка в поле %name% со значением %value%, должно быть строкой.';
                break;
            case CONTENT:
                pattern = /.*/;
                error = 'Ошибка в поле %name% со значением %value%, должно быть контентом.';
                break;
            case URL:
                pattern = /^[a-zA-Z0-9_-]+$/;
                error = 'Ошибка в поле %name% со значением %value%, должно быть url\'ом.';
                break;
            case INTEGER:
                pattern = /^\d+$/;
                error = 'Ошибка в поле %name% со значением %value%, поле должно быть числом.';
                break;
            case EMAIL:
                pattern = /^\w+([\.-]?\w+)*@\w+([\.-]?\w+)*(\.\w{2,3})+$/;
                error = 'Ошибка в поле %name% со значением %value%, поле должно быть email\'oм.';
                break;
            default:
                pattern = /^.{6,}$/;
                break;
        }

        if (!pattern.test(value)) {
            error = error.replace('%name%', name);
            error = error.replace('%value%', value);

            errors.push(error)
        }
    });

    return errors.length > 1 ? errors[errors.length - 1] : errors[0];
}
