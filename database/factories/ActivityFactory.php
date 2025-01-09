<?php

namespace Database\Factories;

use App\Models\Activity;
use Faker\Generator as FakerFactory;
use Illuminate\Database\Eloquent\Factories\Factory;

/**
 * @extends Factory<Activity>
 */
class ActivityFactory extends Factory
{
    /**
     * Define the model's default state.
     *
     * @return array<string, mixed>
     */
    public function definition(): array
    {
        $faker = \Faker\Factory::create('ru_RU');
        $activities = [
            'Еда', 'Мясо', 'Молочные продукты', 'Грузовые автомобили', 'Легковые автомобили',
            'Запчасти', 'Аксессуары', 'Книги', 'Техника', 'Путешествия', 'Развлечения',
            'Ресторанный бизнес', 'Сельское хозяйство', 'Мясные деликатесы', 'Пекарни', 'Кондитерские изделия',
            'Фрукты и овощи', 'Рыба и морепродукты', 'Электроника', 'Мобильные устройства', 'Компьютеры',
            'Мебель', 'Домашний текстиль', 'Строительные материалы', 'Инструменты', 'Декоративные элементы',
            'Одежда', 'Обувь', 'Аксессуары для дома', 'Косметика', 'Парфюмерия', 'Бытовая химия',
            'Игрушки', 'Детские товары', 'Музыкальные инструменты', 'Спортивное оборудование', 'Автозапчасти',
            'Бытовая техника', 'Садоводство', 'Фототехника', 'Часы', 'Украшения', 'Медицинские товары',
            'Фармацевтика', 'Туризм', 'Гостиничный бизнес', 'Логистика', 'Образование', 'Библиотеки',
            'Культурные мероприятия', 'Театр', 'Киноиндустрия'
        ];

        return [
            'name' => $faker->randomElement($activities), // Random Russian activity
            'parent_id' => null, // По умолчанию корневой элемент
        ];
    }

    public function withParent($parentId)
    {
        return $this->state(fn () => ['parent_id' => $parentId]);
    }
}
