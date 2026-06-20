<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;

return new class extends Migration
{
    /**
     * Run the migrations.
     */
    public function up(): void
    {
        Schema::create('personal_access_tokens', function (Blueprint $table) {
            $table->id();
            // tokenable_typeとtokenable_idで、トークンを所有するUserを表す。
            $table->morphs('tokenable');
            // CLI名など、人がトークンの利用元を識別するための名前。
            $table->text('name');
            // 平文ではなく、Sanctumが生成したSHA-256ハッシュだけを保存する。
            $table->string('token', 64)->unique();
            // api:readなど、このトークンに許可した操作をJSONで保存する。
            $table->text('abilities')->nullable();
            // 利用状況と有効期限を確認・管理するための日時。
            $table->timestamp('last_used_at')->nullable();
            $table->timestamp('expires_at')->nullable()->index();
            $table->timestamps();
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('personal_access_tokens');
    }
};
