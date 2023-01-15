<?php

use Database\Builder;
use Database\Migration;

function up()
{
  try {
    Migration::createTableIfNotExists('antenatal_visits', function (Builder $table) {
      $table->id();

      $table->integer('session_id');
      $table->foreign('session_id', 'antenatal_sessions');

      $table->integer('checked_in_by');
      $table->foreign('checked_in_by', 'staff');

      $table->integer('attending_nurse_id', false);
      $table->foreign('attending_nurse_id', 'staff');

      $table->json('vitals', false);

      $table->integer('doc_id', false);
      $table->foreign('doc_id', 'staff');

      $table->datetime('date_submitted', false)->default('CURRENT_TIMESTAMP');
      $table->timestamps();
    });

    Migration::alterTable('antenatal_visits', function (Builder $table) {
      $table->integer('status', false)->default(0)->add('doc_id');
    });
  } catch (Throwable $th) {
  }
}

function down()
{
  // Migration::dropTableIfExists('antenatal_visits');
}

return fn () => up();
