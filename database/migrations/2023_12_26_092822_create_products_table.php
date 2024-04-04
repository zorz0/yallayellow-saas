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
        Schema::create('products', function (Blueprint $table) {
            $table->id();
            $table->string('name');
            $table->string('slug')->nullable();
            $table->string('tag_id')->nullable();
            $table->unsignedBigInteger('maincategory_id')->nullable();
            $table->unsignedBigInteger('subcategory_id')->nullable();
            $table->string('tax_id')->nullable();
            $table->string('tax_status')->nullable();
            $table->integer('shipping_id')->nullable();
            $table->string('preview_type')->nullable();
            $table->string('preview_video')->nullable();
            $table->string('preview_content')->nullable();
            $table->integer('trending')->default('0')->comment('0 => no, 1 => yes');
            $table->integer('status')->default(1)->comment('0 => Inactive, 1 => Active');
            $table->string('video_url')->nullable();
            $table->integer('track_stock')->comment('0 => off, 1 => on');
            $table->string('stock_order_status')->nullable();
            $table->float('price')->default(0);
            $table->float('sale_price')->default(0);
            $table->integer('product_stock')->default(0);
            $table->integer('low_stock_threshold')->default(0);
            $table->string('downloadable_product')->nullable();
            $table->integer('product_weight')->nullable();
            $table->string('cover_image_path')->nullable();
            $table->string('cover_image_url')->nullable();
            $table->string('stock_status')->default(0);
            $table->boolean('variant_product')->default(1)->comment('0 => no variant, 1 => variant');
            $table->string('attribute_id')->nullable();
            $table->text('product_attribute',1000)->nullable();
            $table->boolean('custom_field_status')->default(0)->comment('0 => no, 1 => yes');
            $table->longtext('custom_field')->nullable();
            $table->longText('description')->nullable();
            $table->text('detail')->nullable();
            $table->text('specification')->nullable();
            $table->double('average_rating', 8, 2)->default(0);
            $table->string('theme_id')->nullable();
            $table->integer('store_id');
            $table->integer('created_by');
            $table->integer('is_active')->nullable();

            $table->timestamps();

            $table->foreign('maincategory_id')->references('id')->on('main_categories')->onDelete('cascade');
            $table->foreign('subcategory_id')->references('id')->on('sub_categories')->onDelete('cascade');
        });
    }

    /**
     * Reverse the migrations.
     */
    public function down(): void
    {
        Schema::dropIfExists('products');
    }
};
