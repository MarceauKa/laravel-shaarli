<?php

use Illuminate\Database\Migrations\Migration;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Support\Facades\Schema;
use Spatie\MediaLibrary\MediaCollections\Models\Media;
use Illuminate\Support\Facades\DB;
use \Illuminate\Support\Str;

return new class extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        if ( ! Schema::hasColumn( 'media', 'generated_conversions' ) ) {
            Schema::table( 'media', function ( Blueprint $table ) {
                $table->json( 'generated_conversions' )->nullable();
            } );
        }
        Media::query()
            ->where(function ($query) {
                $query->whereNull('generated_conversions')
                    ->orWhere('generated_conversions', '')
                    ->orWhereRaw("JSON_TYPE(generated_conversions) = 'NULL'");
            })
            ->whereRaw("JSON_LENGTH(custom_properties) > 0")
            ->update([
                'generated_conversions' => DB::raw("JSON_EXTRACT(custom_properties, '$.generated_conversions')"),
            ]);
        if ( ! Schema::hasColumn( 'media', 'conversions_disk' ) ) {
            Schema::table( 'media', function ( Blueprint $table ) {
                $table->string( 'conversions_disk',255 )->nullable();
            } );
        }
        Media::query()
            ->where(function ($query) {
                $query->whereNull('conversions_disk')
                    ->orWhere('conversions_disk', '');
            })
            ->update([
                'conversions_disk' => DB::raw('disk'),
            ]);
        if ( ! Schema::hasColumn( 'media', 'uuid' ) ) {
            Schema::table( 'media', function ( Blueprint $table ) {
                $table->char( 'uuid',36 )->nullable();
            } );
        }
        Media::query()
            ->where(function ($query) {
                $query->whereNull('uuid')
                    ->orWhere('uuid', '');
            })
            ->update([
                'uuid' => Str::uuid(),
            ]);
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        Schema::table('media', function (Blueprint $table) {
            $table->dropColumn('generated_conversions');
            $table->dropColumn('conversions_disk');
            $table->dropColumn('uuid');
        });
    }
};
