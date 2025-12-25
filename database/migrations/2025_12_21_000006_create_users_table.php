<?php

use App\Constants\BrandConstant;
use App\Constants\PasswordResetTokenConstant;
use App\Constants\PersonalAccessTokenConstant;
use App\Constants\SessionConstant;
use App\Constants\UserConstant;
use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class() extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create(UserConstant::TABLE, function (Blueprint $table) {
            $table->uuid(UserConstant::ID)->primary();
            $table->foreignUuid(UserConstant::BRAND_ID)
                ->constrained(BrandConstant::TABLE)
                ->onDelete('cascade');
            $table->string(UserConstant::NAME);
            $table->string(UserConstant::EMAIL)->unique();
            $table->timestamp(UserConstant::EMAIL_VERIFIED_AT)->nullable();
            $table->string(UserConstant::PASSWORD);
            $table->string(UserConstant::ROLE)
                ->default(UserConstant::ROLE_USER);
            $table->rememberToken();
            $table->timestamps();
            $table->softDeletes();

            $table->index([UserConstant::BRAND_ID, UserConstant::ROLE]);
            $table->index(UserConstant::EMAIL);
        });

        Schema::create(PasswordResetTokenConstant::TABLE, function (Blueprint $table) {
            $table->string(PasswordResetTokenConstant::EMAIL)->primary();
            $table->string(PasswordResetTokenConstant::TOKEN);
            $table->timestamp(PasswordResetTokenConstant::CREATED_AT)->nullable();
        });

        Schema::create(SessionConstant::TABLE, function (Blueprint $table) {
            $table->string(SessionConstant::ID)->primary();
            $table->foreignUuid(SessionConstant::USER_ID)->nullable()->index();
            $table->string(SessionConstant::IP_ADDRESS, 45)->nullable();
            $table->text(SessionConstant::USER_AGENT)->nullable();
            $table->longText(SessionConstant::PAYLOAD);
            $table->integer(SessionConstant::LAST_ACTIVITY)->index();
        });

        Schema::create(PersonalAccessTokenConstant::TABLE, function (Blueprint $table) {
            $table->id();
            $table->uuidMorphs(PersonalAccessTokenConstant::TOKENABLE);
            $table->string(PersonalAccessTokenConstant::NAME);
            $table->string(PersonalAccessTokenConstant::TOKEN, 64)->unique();
            $table->text(PersonalAccessTokenConstant::ABILITIES)->nullable();
            $table->timestamp(PersonalAccessTokenConstant::LAST_USED_AT)->nullable();
            $table->timestamp(PersonalAccessTokenConstant::EXPIRES_AT)->nullable();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists(PersonalAccessTokenConstant::TABLE);
        Schema::dropIfExists(SessionConstant::TABLE);
        Schema::dropIfExists(PasswordResetTokenConstant::TABLE);
        Schema::dropIfExists(UserConstant::TABLE);
    }
};
