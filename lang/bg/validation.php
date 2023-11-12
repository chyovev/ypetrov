<?php

return [

    /*
    |--------------------------------------------------------------------------
    | Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | The following language lines contain the default error messages used by
    | the validator class. Some of these rules have multiple versions such
    | as the size rules. Feel free to tweak each of these messages here.
    |
    */

    'accepted'        => 'Полето :attribute трябва да бъде прието.',
    'accepted_if'     => 'Полето :attribute трябва да бъде прието, в случай че стойността на :other е :value.',
    'active_url'      => 'Стойността на полето :attribute трябва да е активен URL адрес.',
    'after'           => 'Стойността на полето :attribute трябва да е дата след :date.',
    'after_or_equal'  => 'Стойността на Полето :attribute трябва да е дата, равна на или след :date.',
    'alpha'           => 'Стойността на Полето :attribute може да съдържа само букви на латиница.',
    'alpha_dash'      => 'Стойността на полето :attribute може да съдържа само букви на латиница, числа, тирета и долни черти.',
    'alpha_num'       => 'Стойността на полето :attribute може да съдържа само букви на латиница и числа.',
    'array'           => 'Стойността на полето :attribute трябва да бъде масив.',
    'ascii'           => 'Стойността на полето :attribute може да съдържа само еднобайтови символи.',
    'before'          => 'Стойността на полето :attribute трябва да е дата преди :date.',
    'before_or_equal' => 'Стойността на полето :attribute трябва да е дата преди или равна на :date.',
    'between' => [
        'array'   => 'Полето :attribute трябва да съдържа между :min и :max елемента.',
        'file'    => 'Полето :attribute трябва да в килобайтовия диапазон :min – :max KB.',
        'numeric' => 'Полето :attribute трябва да има стойност между :min и :max.',
        'string'  => 'Полето :attribute трябва да има дължина, не по-малка от :min и не по-голяма от:max символа.',
    ],
    'boolean'           => 'Полето :attribute може да има само булева стойност (true или false).',
    'can'               => 'Полето :attribute съдържа непозволена стойност.',
    'confirmed'         => 'Потвърждението за полето :attribute не съвпада.',
    'current_password'  => 'Неправилна парола.',
    'date'              => 'Стойността на полето :attribute трябва да е валидна дата.',
    'date_equals'       => 'Стойността на полето :attribute трябва да е датата :date.',
    'date_format'       => 'Стойността на полето :attribute трябва да отговаря на формата :format.',
    'decimal'           => 'Стойността на полето :attribute трябва да има :decimal знака след десетичния знак.',
    'declined'          => 'Стойността на полето :attribute трябва да е негативна.',
    'declined_if'       => 'Стойността на полето :attribute трябва да е негативна, в случай че полето :other е :value.',
    'different'         => 'Стойността на полето :attribute трябва да е различна от тази на :other.',
    'digits'            => 'Стойността на полето :attribute трябва да е точно :digits знака.',
    'digits_between'    => 'Стойността на полето :attribute трябва да е има между :min и :max знака.',
    'dimensions'        => 'Полето :attribute има неправилни пропорции.',
    'distinct'          => 'Полето :attribute съдържа повтаряща се стойност.',
    'doesnt_end_with'   => 'Стойността на полето :attribute не трябва да завършва с една от следните възможности: :values.',
    'doesnt_start_with' => 'Стойността на полето :attribute не трябва да започва с една от следните възможности: :values.',
    'email'             => 'Стойността на полето :attribute трябва да е валиден имейл адрес.',
    'ends_with'         => 'Стойността на полето :attribute трябва да завършва с една от следните възможности: :values.',
    'enum'              => 'Избраният елемент за полето  :attribute е невалиден.',
    'exists'            => 'Избраният елемент за полето :attribute не съществува.',
    'file'              => 'Полето :attribute трябва да съдържа файл.',
    'filled'            => 'Стойността на полето :attribute трябва да е попълнена.',
    'gt' => [
        'array'   => 'Стойността на полето :attribute трябва да съдържа повече от посочения брой елементи: :value.',
        'file'    => 'Стойността на полето :attribute трябва да надвишава :value KB.',
        'numeric' => 'Стойността на полето :attribute трябва да е по-голяма от :value.',
        'string'  => 'Стойността на полето :attribute трябва да има повече от :value символа.',
    ],
    'gte' => [
        'array'   => 'Стойността на полето :attribute трябва да съдържа поне :value или повече елемента.',
        'file'    => 'Стойността на полето :attribute трябва да е поне :value KB или повече.',
        'numeric' => 'Стойността на полето :attribute трябва да е равна на или по-голяма от :value.',
        'string'  => 'Стойността на полето :attribute трябва да е равна на или по-голяма от :value символа.',
    ],
    'image'     => 'Полето :attribute трябва да е изображение.',
    'in'        => 'Избраната стойност за полето :attribute е невалидна.',
    'in_array'  => 'Стойността на полето :attribute трябва да фигурира в :other.',
    'integer'   => 'Стойността на полето :attribute трябва да е от целочислен тип.',
    'ip'        => 'Стойността на полето :attribute трябва да е валиден IP адрес.',
    'ipv4'      => 'Стойността на полето :attribute трябва да е валиден IPv4 адрес.',
    'ipv6'      => 'Стойността на полето :attribute трябва да е валиден IPv6 адрес.',
    'json'      => 'Стойността на полето :attribute трябва да е във валиден JSON формат.',
    'lowercase' => 'Стойността на полето :attribute трябва да се състои само от малки букви.',
    'lt' => [
        'array'   => 'Стойността на полето :attribute трябва да е по-малка от посочения брой елементи: :value.',
        'file'    => 'Стойността на полето :attribute трябва да е по-малка от :value KB.',
        'numeric' => 'Стойността на полето :attribute трябва да е по-малка от :value.',
        'string'  => 'Стойността на полето :attribute трябва да е по-малка от :value символа.',
    ],
    'lte' => [
        'array'   => 'Стойността на полето :attribute трябва да е по-малка или равна на посочения брой елементи: :value.',
        'file'    => 'Стойността на полето :attribute трябва да е по-малка или равна на :value KB.',
        'numeric' => 'Стойността на полето :attribute трябва да е по-малка или равна на :value.',
        'string'  => 'Стойността на полето :attribute трябва да е по-малка или равна на :value символа.',
    ],
    'mac_address' => 'Стойността на полето :attribute трябва да е валиден MAC адрес.',
    'max' => [
        'array'   => 'Стойността на полето :attribute не може да надвишава посочения брой елементи: :max.',
        'file'    => 'Стойността на полето :attribute не бива да надвишава :max KB.',
        'numeric' => 'Стойността на полето :attribute не бива да надвишава :max.',
        'string'  => 'Стойността на полето :attribute не бива да надвишава :max символа.',
    ],
    'max_digits' => 'Стойността на полето :attribute не трябва да има повече от :max цифри.',
    'mimes'      => 'MIME типът на полето :attribute трябва да отговаря на една от посочените възможности: :values.',
    'mimetypes'  => 'MIME типът на полето :attribute трябва да отговаря на една от посочените възможности: :values.',
    'min' => [
        'array'   => 'Стойността на полето :attribute трябва да се състои поне от минималния посочен брой елементи: :min.',
        'file'    => 'Стойността на полето :attribute трябва да е поне :min KB.',
        'numeric' => 'Стойността на полето :attribute трябва да е по-голяма от :min.',
        'string'  => 'Стойността на полето :attribute трябва да е поне :min символа.',
    ],
    'min_digits'       => 'Стойността на полето :attribute трябва да има поне :min цифри.',
    'missing'          => 'Стойността на полето :attribute не трябва да фигурира в заявката.',
    'missing_if'       => 'Стойността на полето :attribute не трябва да фигурира в заявката, в случай че :other е :value.',
    'missing_unless'   => 'Стойността на полето :attribute не трябва да фигурира в заявката, освен ако :other не е :value.',
    'missing_with'     => 'Стойността на полето :attribute не трябва да фигурира в заявката само ако :values е налично.',
    'missing_with_all' => 'Стойността на полето :attribute не трябва да фигурира в заявката само ако следните полета са налични: :values.',
    'multiple_of'      => 'Стойността на полето :attribute трябва да е кратна на :value.',
    'not_in'           => 'Избраната стойност за полето :attribute е невалидна.',
    'not_regex'        => 'Стойността на полето :attribute не трябва да отговаря на посочения формат.',
    'numeric'          => 'Стойността на полето :attribute трябва да бъде от числен тип.',
    'password' => [
        'letters'       => 'The :attribute field must contain at least one letter.',
        'mixed'         => 'The :attribute field must contain at least one uppercase and one lowercase letter.',
        'numbers'       => 'The :attribute field must contain at least one number.',
        'symbols'       => 'The :attribute field must contain at least one symbol.',
        'uncompromised' => 'The given :attribute has appeared in a data leak. Please choose a different :attribute.',
    ],
    'present'              => 'Полето :attribute трябва да фигурира в заявката.',
    'prohibited'           => 'Полето :attribute не трябва да фигурира в заявката.',
    'prohibited_if'        => 'Полето :attribute не трябва да фигурира в заявката, в случай че :other е :value.',
    'prohibited_unless'    => 'Полето :attribute не трябва да фигурира в заявката, освен ако :other не е :values.',
    'prohibits'            => 'Наличието на полето :attribute изисква следните полета да не са налични в заявката: :other.',
    'regex'                => 'Стойността на полето :attribute не отговаря на посочения формат.',
    'required'             => 'Полето :attribute е задължително.',
    'required_array_keys'  => 'Полето :attribute трябва да бъде масив, съдържащ следните елементи: :values.',
    'required_if'          => 'Полето :attribute е задължително, в случай че :other е :value.',
    'required_if_accepted' => 'Полето :attribute е задължително, в случай че :other е прието.',
    'required_unless'      => 'Полето :attribute е задължително, освен ако :other не е :values.',
    'required_with'        => 'Полето :attribute е задължително, в случай че следните полета са въведени: :values.',
    'required_with_all'    => 'Полето :attribute е задължително, в случай че всички от изброените полета са въведени: :values.',
    'required_without'     => 'Полето :attribute е задължително, в случай че следните полета не са въведени: :values.',
    'required_without_all' => 'Полето :attribute е задължително, в случай че всички от изброените полета не са въведени: :values.',
    'same'                 => 'Стойността на полето :attribute трябва да е същата като тази на полето :other.',
    'size' => [
        'array'   => 'Стойността на полето :attribute трябва да е съставена от :size елемента.',
        'file'    => 'Стойността на полето :attribute трябва да е :size KB.',
        'numeric' => 'Стойността на полето :attribute трябва да е :size.',
        'string'  => 'Стойността на полето :attribute трябва да е :size символа.',
    ],
    'starts_with' => 'Стойността на полето :attribute трябва да започва с някоя от следващите възможности: :values.',
    'string'      => 'Стойността на полето :attribute трябва да е от низов тип.',
    'timezone'    => 'Стойността на полето :attribute трябва да е валидна времева зона.',
    'unique'      => 'Вече съществува запис с такава стойност за полето :attribute.',
    'uploaded'    => 'Възникна проблем при качването на поле :attribute.',
    'uppercase'   => 'Стойността на полето :attribute трябва да се състои само от главни букви.',
    'url'         => 'Стойността на полето :attribute трябва да е валиден URL адрес.',
    'ulid'        => 'Стойността на полето :attribute трябва да е валиден ULID.',
    'uuid'        => 'Стойността на полето :attribute трябва да е валиден UUID.',

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Language Lines
    |--------------------------------------------------------------------------
    |
    | Here you may specify custom validation messages for attributes using the
    | convention "attribute.rule" to name the lines. This makes it quick to
    | specify a specific custom language line for a given attribute rule.
    |
    */

    'custom' => [
        'attribute-name' => [
            'rule-name' => 'custom-message',
        ],
    ],

    /*
    |--------------------------------------------------------------------------
    | Custom Validation Attributes
    |--------------------------------------------------------------------------
    |
    | The following language lines are used to swap our attribute placeholder
    | with something more reader friendly such as "E-Mail Address" instead
    | of "email". This simply helps us make our message more expressive.
    |
    */

    'attributes' => [],

];
