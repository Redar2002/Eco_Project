<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Schema;

/**
 * Date d'envoi de l'e-mail de confirmation.
 * Sert à empêcher les doublons et à relancer les envois en échec (valeur nulle).
 */
return new class extends Migration
{
    public function up(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->timestamp('confirmation_sent_at')->nullable()->after('status');
        });

        // Les commandes existantes ont déjà été traitées : on ne leur renverra pas de confirmation
        DB::table('orders')->update(['confirmation_sent_at' => DB::raw('created_at')]);
    }

    public function down(): void
    {
        Schema::table('orders', function (Blueprint $table) {
            $table->dropColumn('confirmation_sent_at');
        });
    }
};
