<?php

use Illuminate\Support\Facades\Schema;
use Illuminate\Database\Schema\Blueprint;
use Illuminate\Database\Migrations\Migration;

class CreateOrderStatusTrigger extends Migration
{
    /**
     * Run the migrations.
     *
     * @return void
     */
    public function up()
    {
        //
        DB::unprepared('
            CREATE TRIGGER update_order_status After UPDATE ON items
            FOR EACH ROW
            BEGIN
              declare $countItems int;
              declare $countDeliveredItems int;
              
              if ( (new.order_id is null) and (old.order_id is null))  then
				begin
				end;
              else
                begin
                    if new.order_id is not null then
                        begin
                            select count(id) into $countItems from items where order_id = new.order_id;
                            select count(id) into $countDeliveredItems from items where order_id = new.order_id and physical_status= "Delivered";
                            if ($countItems = 0) then
                                update orders set status = "Cancelled" where id = new.order_id;
                            elseif ($countItems = $countDeliveredItems) then
                                update orders set status = "Completed" where id = new.order_id;
							else
								update orders set status = "In progress" where id = new.order_id;
                            end if;
                        end;
                    end if;
                    if old.order_id is not null then
                        begin
                            select count(id) into $countItems from items where order_id = old.order_id;
                            select count(id) into $countDeliveredItems from items where order_id = old.order_id and physical_status= "Delivered";
                            if ($countItems = 0) then
                                update orders set status = "Cancelled" where id = old.order_id;
                            elseif ($countItems = $countDeliveredItems) then
                                update orders set status = "Completed" where id = old.order_id;
							else
								update orders set status = "In progress" where id = new.order_id;
                            end if;
                        end;
                    end if;
                end;
              end if;
            END;
        ');
    }

    /**
     * Reverse the migrations.
     *
     * @return void
     */
    public function down()
    {
        //
        DB::unprepared('DROP TRIGGER `update_order_status`');
    }
}
