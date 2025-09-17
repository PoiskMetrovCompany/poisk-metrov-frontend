<?php
// TODO: переименовать в "0000_00_00_000000_init_tables.php"
use App\Core\Common\DocumentTypeEnum;
use App\Core\Common\VacancyStatusesEnum;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration {
    public function up()
    {
        // Tables
        Schema::create('cities', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key')->unique();
            $table->string('title', 255)->nullable();
            $table->string('slug', 255)->nullable();
            // Indexes
            $table->unique('slug');
        });

        Schema::create('accounts', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key')->unique();
            $table->string('role', 255)->nullable();
            $table->string('phone', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('secret', 255)->nullable();
            // Indexes
            $table->unique('phone');
            $table->unique('email');
        });

        Schema::create('authorization_calls', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('phone', 32);
            $table->string('pincode', 32);
            $table->string('call_id', 32);
        });

        Schema::create('builders', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key')->nullable();
            $table->string('construction', 255)->nullable();
            $table->string('builder', 255)->nullable();
            $table->string('city', 32);
        });

        Schema::create('call_back_phones', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('name', 255)->nullable();
            $table->string('phone', 32);
        });

        Schema::create('chat_token_c_r_m_lead_pairs', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('chat_token', 32);
            $table->string('crm_city', 32);
            $table->integer('crm_id');
        });

        Schema::create('current_surveys', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->integer('chat_id');
            $table->string('date', 255)->nullable();
            $table->string('agent_fio', 255)->nullable();
            $table->string('client', 255)->nullable();
            $table->string('is_first', 255)->nullable();
            $table->string('construction', 255)->nullable();
            $table->string('builder', 255)->nullable();
            $table->string('address', 255)->nullable();
            $table->string('is_lead', 255)->nullable();
            $table->bigInteger('price')->nullable();
            $table->string('builder_percent', 10)->nullable();
            $table->bigInteger('commission')->nullable();
            $table->string('place', 64)->nullable();
            $table->string('approximate_name', 255)->nullable();
            $table->string('approximate_builder', 255)->nullable();
            $table->string('approximate_construction', 255)->nullable();
            $table->string('current_step', 255)->default('approximate_name');
            $table->boolean('awaiting_confirmation')->default(false);
            $table->boolean('confirmed')->default(false);
            $table->string('document', 255)->nullable();
        });

        Schema::create('group_chat_bot_messages', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->text('message')->nullable();
            $table->string('sender_chat_token', 32);
            $table->bigInteger('message_id');
            $table->bigInteger('group_id');
        });
        // TODO: искоренить
        Schema::create('journals', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->enum('type', ['synchronizeFeed']);
            $table->enum('status', ['Загружено', 'Частично загружено', 'В обработке', 'Ошибка загрузки']);
            $table->string('name', 255);
            $table->integer('found_objects');
            $table->integer('loaded_objects');
        });

        Schema::create('locations', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->string('country', 255);
            $table->string('region', 255);
            $table->string('code', 255);
            $table->string('capital', 255);
            $table->string('district', 255);
            $table->string('locality', 255);
            // Добавляем уникальный индекс для столбца key
            $table->unique('key');
        });

        Schema::create('marital_statuses', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->enum('title', ['Состою в зарегистрированном браке', 'Состою в незарегистрированном браке', 'Не состою в браке'])->default('Не состою в браке');
        });

        Schema::create('residential_complex_categories', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->string('category_name', 255);
            $table->unique('key');
        });

        Schema::create('reservations', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            // Indexes
            $table->unique('key');
        });

        Schema::create('user_ads_agreements', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('phone', 32);
            $table->string('name', 255)->nullable();
            $table->boolean('agreement');
        });

        Schema::create('users', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->integer('crm_id')->nullable();
            $table->rememberToken();
            $table->string('api_token', 80)->nullable();
            $table->string('chat_token', 32)->nullable();
            $table->string('name', 255)->nullable();
            $table->string('email', 255)->nullable();
            $table->string('password', 255)->nullable();
            $table->string('role', 32)->default('user');
            $table->string('phone', 32);
            $table->string('surname', 255)->nullable();
            $table->string('patronymic', 255)->nullable();
            $table->string('crm_city', 32)->nullable();
            $table->boolean('is_test')->default(false);
            // Indexes
            $table->unique('key');
            $table->unique('email');
            $table->unique('api_token');
        });

        Schema::create('vacancies', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->string('title', 255);
        });

        Schema::create('websockets_statistics_entries', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('app_id', 255);
            $table->integer('peak_connection_count');
            $table->integer('websocket_message_count');
            $table->integer('api_message_count');
        });

        Schema::create('residential_complexes', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('location_key');
            $table->string('code', 255);
            $table->string('old_code', 255)->nullable();
            $table->string('name', 255);
            $table->string('builder', 255);
            $table->longText('description');
            $table->double('latitude', 16, 10);
            $table->double('longitude', 16, 10);
            $table->string('address', 255);
            $table->string('metro_station', 255)->nullable();
            $table->smallInteger('metro_time')->nullable();
            $table->string('metro_type', 255)->nullable();
            $table->longText('infrastructure')->nullable();
            $table->string('parking', 255)->nullable();
            $table->string('panorama', 255)->nullable();
            $table->tinyInteger('corpuses')->nullable();
            $table->json('meta')->nullable();
            $table->string('elevator', 64)->nullable();
            $table->string('primary_material', 64)->nullable();
            $table->tinyInteger('floors')->nullable();
            $table->double('primary_ceiling_height', 8, 2)->nullable();
            $table->boolean('on_main_page')->default(false);
            $table->string('head_title', 255)->nullable();
            $table->string('h1', 255)->nullable();
            // Indexes
            $table->unique('code');
            $table->unique('key');
            // Fk
            $table->foreign('location_key')->references('key')->on('locations');
        });

        Schema::create('buildings', function (Blueprint $table) {
            $table->id();
            $table->uuid('key');
            $table->uuid('complex_key');
            $table->string('building_materials', 64)->nullable();
            $table->string('building_state', 64)->nullable();
            $table->string('building_phase', 64)->nullable();
            $table->string('building_section', 64)->default('Корпус 1');
            $table->tinyInteger('floors_total')->nullable();
            $table->double('latitude', 16, 10)->nullable();
            $table->double('longitude', 16, 10)->nullable();
            $table->tinyInteger('ready_quarter')->nullable();
            $table->smallInteger('built_year')->nullable();
            // Indexes
            $table->unique('key');
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('managers', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('crm_id')->nullable();
            $table->bigInteger('user_id')->nullable();
            $table->bigInteger('telegram_id')->nullable();
            $table->uuid('key');
            $table->uuid('user_key')->nullable();
            $table->string('phone', 32);
            $table->string('document_name', 255);
            $table->string('avatar_file_name', 255)->nullable();
            $table->string('city', 32);
            $table->boolean('autokick_immune')->default(false);
            // Indexes
            $table->unique('key');
            // Fk
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('chat_sessions', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('chat_token', 32);
            $table->integer('manager_telegram_id')->nullable();
            $table->bigInteger('manager_id')->nullable();
            $table->uuid('manager_key')->nullable();
            // Fk
            $table->foreign('manager_key')->references('key')->on('managers');
        });

        Schema::create('c_r_m_sync_required_for_users', function (Blueprint $table) {
            $table->id();
            $table->bigInteger('user_id');
            $table->timestamps();
            $table->uuid('user_key')->nullable();
            // Fk
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('deleted_favorite_buildings', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('user_id');
            $table->string('complex_code', 255);
            $table->uuid('user_key')->nullable();
            // Fk
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('files', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            // Исправляем тип данных на unsignedBigInteger
            $table->uuid('user_key');
            $table->uuid('key');
            $table->string('name', 255);
            $table->string('input_name', 255);
            $table->string('mime', 255);
            $table->string('size', 255);
            $table->string('extension', 255);
            $table->string('category', 255);
            $table->string('comment', 255)->nullable();
            // Indexes
            $table->unique('key');
            $table->index('extension');
            $table->index('category');
            $table->index('comment');
            // Fk
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('apartments', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('offer_id', 64)->nullable();
            $table->uuid('key');
            $table->bigInteger('complex_id')->nullable();
            $table->string('apartment_type', 255)->nullable();
            $table->string('renovation', 255)->nullable();
            $table->string('balcony', 255)->nullable();
            $table->string('bathroom_unit', 255)->nullable();
            $table->tinyInteger('floor');
            $table->string('apartment_number', 32);
            $table->string('plan_URL', 255)->nullable();
            $table->double('ceiling_height', 8, 2)->nullable();
            $table->tinyInteger('room_count');
            $table->bigInteger('price');
            $table->double('area', 8, 2);
            $table->double('living_space', 8, 2)->nullable();
            $table->double('kitchen_space', 8, 2)->nullable();
            $table->string('floor_plan_url', 255)->nullable();
            $table->string('windows_directions', 255)->nullable();
            $table->json('meta')->nullable();
            $table->string('feed_source', 255)->nullable();
            $table->string('head_title', 255)->nullable();
            $table->string('h1', 255)->nullable();
            $table->uuid('complex_key')->nullable();
            $table->uuid('building_key')->nullable();
            // Indexes
            $table->unique('key');
            $table->unique('offer_id');
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
            $table->foreign('building_key')->references('key')->on('buildings');
        });

        Schema::create('amenities', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('complex_id');
            $table->string('amenity', 255);
            $table->uuid('complex_key')->nullable();
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('best_offers', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('complex_code', 255);
            $table->string('location_code', 255);
            $table->uuid('complex_key')->nullable();
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('building_process', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('complex_id');
            $table->string('image_url', 255);
            $table->date('date')->nullable();
            $table->uuid('complex_key')->nullable();
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('docs', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('complex_id');
            $table->string('doc_url', 255);
            $table->string('doc_name', 255);
            $table->uuid('complex_key')->nullable();
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('galleries', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('building_key');
            $table->string('image_url', 256);
            // Indexes
            $table->unique('image_url');
            // Fk
            $table->foreign('building_key')->references('key')->on('buildings');
        });

        Schema::create('residential_complex_apartment_specifics', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('complex_key')->nullable();
            $table->uuid('building_key');
            $table->bigInteger('starting_price');
            $table->double('starting_area', 8, 2);
            $table->smallInteger('count');
            $table->string('display_name', 16);
            // Fk
            $table->foreign('building_key')->references('key')->on('buildings');
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('residential_complex_category_pivots', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('complex_id');
            $table->bigInteger('category_id');
            $table->uuid('complex_key')->nullable();
            $table->uuid('category_key')->nullable();
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
            $table->foreign('category_key')->references('key')->on('residential_complex_categories');
        });

        Schema::create('sprite_image_positions', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('building_key');
            $table->uuid('complex_key')->nullable();
            $table->string('filepath', 512);
            $table->smallInteger('x');
            $table->smallInteger('y');
            $table->smallInteger('size_x');
            $table->smallInteger('size_y');
            // Indexes
            $table->unique('filepath');
            // Fk
            $table->foreign('building_key')->references('key')->on('buildings');
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
        });

        Schema::create('user_favorite_buildings', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('user_id');
            $table->uuid('complex_key')->nullable();
            $table->uuid('user_key')->nullable();
            $table->string('complex_code', 255);
            // Fk
            $table->foreign('complex_key')->references('key')->on('residential_complexes');
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('visited_pages', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('user_id');
            $table->string('page', 255);
            $table->string('code', 255);
            $table->uuid('user_key')->nullable();
            // Fk
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('apartment_histories', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('apartment_id');
            $table->bigInteger('price');
            $table->uuid('apartment_key')->nullable();
            // Fk
            $table->foreign('apartment_key')->references('key')->on('apartments');
        });

        Schema::create('mortgage_types', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('apartment_id');
            $table->uuid('apartment_key')->nullable();
            $table->string('type', 255);
            // Fk
            $table->foreign('apartment_key')->references('key')->on('apartments');
        });

        Schema::create('renovation_urls', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->string('offer_id', 64);
            $table->uuid('apartment_key')->nullable();
            $table->string('renovation_url', 255);

            // Fk
            $table->foreign('apartment_key')->references('key')->on('apartments');
        });

        Schema::create('user_favorite_plans', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('user_id');
            $table->uuid('apartment_key')->nullable();
            $table->uuid('user_key')->nullable();
            $table->string('offer_id', 64);
            // Fk
            $table->foreign('apartment_key')->references('key')->on('apartments');
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('reservations_details', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('reservation_key');
            // Indexes
            $table->unique('reservation_key');
        });

        Schema::create('booked_orders', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('reservation_key');
            // Indexes
            $table->unique('key');
            // Fk
            $table->foreign('reservation_key')->references('key')->on('reservations')->onDelete('cascade');
        });

        Schema::create('borrowers', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('booked_order_key')->nullable();
            $table->string('fio', 255);
            $table->date('birth_date');
            $table->string('citizenship', 255);
            $table->string('education', 255);
            $table->string('marital_status', 255);
            $table->bigInteger('income');
            $table->boolean('is_primary');
            // Indexes
            $table->unique('key');
            // Fk
            $table->foreign('booked_order_key')->references('key')->on('booked_orders')->nullOnDelete();
        });

        Schema::create('borrower_documents', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('borrower_key');
            $table->uuid('file_key');
            $table->enum('documents_type', [DocumentTypeEnum::PASSPORT->value]);
            // Fk
            $table->foreign('borrower_key')->references('key')->on('borrowers');
        });

        Schema::create('borrower_passports', function (Blueprint $table) {
            $table->id();
            $table->uuid('key');
            $table->uuid('borrower_key');
            $table->integer('number');
            $table->date('issue_date');
            $table->integer('code');
            $table->string('issued_by', 255);
            $table->string('place_of_birth', 255);
            $table->string('registration_address_ru', 255);
            // Indexes
            $table->index('issue_date');
            $table->unique('number');
            $table->unique('code');
            // Fk
            $table->foreign('borrower_key')->references('key')->on('borrowers')->onDelete('cascade');
        });

        Schema::create('borrower_works', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->uuid('key');
            $table->uuid('borrower_key');
            $table->string('organization_name', 255);
            $table->string('inn', 255);
            $table->string('phone', 255);
            $table->string('job_title', 255);
            $table->string('employment_contract', 255);
            $table->string('category_position_held', 255);
            $table->integer('number_of_employees');
            $table->integer('experience');
            // Fk
            $table->foreign('borrower_key')->references('key')->on('borrowers')->onDelete('cascade');
        });

        // Schema::create('candidate_profiles', function (Blueprint $table) {
        //     $table->id();
        //     $table->timestampTz('created_at')->useCurrent();
        //     $table->timestampTz('updated_at')->useCurrent();
        //     $table->timestampTz('deleted_at')->nullable(true)->default(null);
        //     $table->uuid('key');
        //     $table->uuid('vacancies_key');
        //     $table->uuid('marital_statuses_key');
        //     $table->string('work_team')->default('Административный состав');
        //     $table->string('city_work', 255)->nullable();
        //     $table->enum('status', [
        //         VacancyStatusesEnum::New->value,
        //         VacancyStatusesEnum::Verified->value,
        //         VacancyStatusesEnum::Rejected->value,
        //         VacancyStatusesEnum::NeedsImprovement->value,
        //         VacancyStatusesEnum::Accepted->value,
        //         VacancyStatusesEnum::NotAccepted->value,
        //         VacancyStatusesEnum::CameOut->value,
        //         VacancyStatusesEnum::NotCameOut->value,
        //     ])->default('Новая анкета');
        //     $table->string('first_name', 255);
        //     $table->string('last_name', 255);
        //     $table->string('middle_name', 255);
        //     $table->string('reason_for_changing_surnames', 255)->nullable();
        //     $table->text('courses')->nullable();
        //     $table->date('birth_date');
        //     $table->string('country_birth', 255);
        //     $table->string('city_birth', 255);
        //     $table->enum('level_educational', ['Высшее', 'Неоконченное высшее', 'Среднее специальное', 'Среднее общее'])->nullable();
        //     $table->json('educational_institution');
        //     $table->string('organization_name', 255)->nullable();
        //     $table->string('organization_phone', 255)->nullable();
        //     $table->string('field_of_activity', 255)->nullable();
        //     $table->string('organization_address', 255)->nullable();
        //     $table->string('organization_job_title', 255)->nullable();
        //     $table->string('organization_price', 255)->nullable();
        //     $table->date('date_of_hiring')->nullable();
        //     $table->date('date_of_dismissal')->nullable();
        //     $table->string('reason_for_dismissal', 255)->nullable();
        //     $table->string('recommendation_contact', 255)->nullable();
        //     $table->string('mobile_phone_candidate', 100);
        //     $table->string('home_phone_candidate', 100);
        //     $table->string('mail_candidate', 255);
        //     $table->string('inn', 20);
        //     $table->string('passport_series', 4);
        //     $table->string('passport_number', 6);
        //     $table->string('passport_issued', 255);
        //     $table->string('permanent_registration_address', 255);
        //     $table->string('temporary_registration_address', 255);
        //     $table->string('actual_residence_address', 255);
        //     $table->json('family_partner')->nullable();
        //     $table->json('adult_family_members')->nullable();
        //     $table->json('adult_children')->nullable();
        //     $table->boolean('serviceman')->default(false);
        //     $table->string('law_breaker', 255);
        //     $table->string('legal_entity', 255);
        //     $table->boolean('is_data_processing')->default(false);
        //     $table->text('comment');

        //     // Indexes
        //     $table->unique('inn');
        //     $table->unique('passport_number');
        //     $table->unique('mobile_phone_candidate');
        //     $table->unique('home_phone_candidate');
        //     $table->unique('mail_candidate');
        //     $table->index('vacancies_key');
        //     $table->index('marital_statuses_key');
        //     $table->index('city_work');
        // });

        Schema::create('interactions', function (Blueprint $table) {
            $table->id();
            $table->uuid('key');
            $table->uuid('apartment_key');
            $table->uuid('manager_key');
            $table->uuid('user_key');
            $table->uuid('reservation_key');
            // Indexes
            $table->unique('key');
            // Fk
            $table->foreign('apartment_key')->references('key')->on('apartments');
            $table->foreign('manager_key')->references('key')->on('managers');
            $table->foreign('reservation_key')->references('key')->on('reservations')->onDelete('cascade');
            $table->foreign('user_key')->references('key')->on('users');
        });

        Schema::create('manager_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->integer('manager_telegram_id')->nullable();
            $table->uuid('chat_session_key');
            $table->uuid('manager_key')->nullable();
            $table->text('message')->nullable();
            // Fk
            $table->foreign('manager_key')->references('key')->on('managers');
        });

        Schema::create('user_chat_messages', function (Blueprint $table) {
            $table->id();
            $table->timestampTz('created_at')->useCurrent();
            $table->timestampTz('updated_at')->useCurrent();
            $table->timestampTz('deleted_at')->nullable(true)->default(null);
            $table->bigInteger('chat_session_id');
            $table->string('chat_token', 32);
            $table->text('message')->nullable();
        });
    }

    public function down()
    {
        Schema::dropIfExists('user_chat_messages');
        Schema::dropIfExists('manager_chat_messages');
        Schema::dropIfExists('interactions');
        // Schema::dropIfExists('candidate_profiles');
        Schema::dropIfExists('borrower_works');
        Schema::dropIfExists('borrower_passports');
        Schema::dropIfExists('borrower_documents');
        Schema::dropIfExists('borrowers');
        Schema::dropIfExists('booked_orders');
        Schema::dropIfExists('reservations_details');
        Schema::dropIfExists('user_favorite_plans');
        Schema::dropIfExists('renovation_urls');
        Schema::dropIfExists('mortgage_types');
        Schema::dropIfExists('apartment_histories');
        Schema::dropIfExists('visited_pages');
        Schema::dropIfExists('user_favorite_buildings');
        Schema::dropIfExists('sprite_image_positions');
        Schema::dropIfExists('residential_complex_category_pivots');
        Schema::dropIfExists('residential_complex_apartment_specifics');
        Schema::dropIfExists('galleries');
        Schema::dropIfExists('docs');
        Schema::dropIfExists('building_process');
        Schema::dropIfExists('best_offers');
        Schema::dropIfExists('amenities');
        Schema::dropIfExists('apartments');
        Schema::dropIfExists('files');
        Schema::dropIfExists('deleted_favorite_buildings');
        Schema::dropIfExists('c_r_m_sync_required_for_users');
        Schema::dropIfExists('chat_sessions');
        Schema::dropIfExists('managers');
        Schema::dropIfExists('buildings');
        Schema::dropIfExists('residential_complexes');
        Schema::dropIfExists('websockets_statistics_entries');
        Schema::dropIfExists('vacancies');
        Schema::dropIfExists('users');
        Schema::dropIfExists('user_ads_agreements');
        Schema::dropIfExists('reservations');
        Schema::dropIfExists('residential_complex_categories');
        Schema::dropIfExists('marital_statuses');
        Schema::dropIfExists('locations');
        Schema::dropIfExists('group_chat_bot_messages');
        Schema::dropIfExists('current_surveys');
        Schema::dropIfExists('chat_token_c_r_m_lead_pairs');
        Schema::dropIfExists('call_back_phones');
        Schema::dropIfExists('builders');
        Schema::dropIfExists('authorization_calls');
        Schema::dropIfExists('accounts');
        Schema::dropIfExists('cities');
    }
};
