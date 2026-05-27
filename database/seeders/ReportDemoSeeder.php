<?php
namespace Database\Seeders;
use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class ReportDemoSeeder extends Seeder
{
    public function run()
    {
        $now = Carbon::now();
        // === CLEANUP OLD DEMO DATA ===
        $demoBookingIds = DB::table('bookings')->where('booking_code','like','DEMO-BK-%')->pluck('id');
        if ($demoBookingIds->count()) {
            DB::table('payments')->whereIn('booking_id', $demoBookingIds)->delete();
            DB::table('passengers')->whereIn('booking_id', $demoBookingIds)->delete();
            DB::table('bookings')->whereIn('id', $demoBookingIds)->delete();
        }
        DB::table('reviews')->whereIn('user_id', DB::table('users')->where('email','like','demo.customer%')->pluck('id'))->delete();
        $demoTourIds = DB::table('tours')->where('slug','like','demo-%')->pluck('id');
        if ($demoTourIds->count()) {
            DB::table('tour_images')->whereIn('tour_id', $demoTourIds)->delete();
            DB::table('tour_destinations')->whereIn('tour_id', $demoTourIds)->delete();
            DB::table('departures')->whereIn('tour_id', $demoTourIds)->delete();
            DB::table('tours')->whereIn('id', $demoTourIds)->delete();
        }
        DB::table('destinations')->where('description','like','%[DEMO]%')->delete();
        DB::table('users')->where('email','like','demo.customer%')->delete();

        echo "Cleaned old demo data.\n";

        // === 1. DESTINATIONS (50) ===
        $destData = [
            ['Hà Nội','Hà Nội','Miền Bắc'],['Vịnh Hạ Long','Quảng Ninh','Miền Bắc'],['Sa Pa','Lào Cai','Miền Bắc'],
            ['Ninh Bình','Ninh Bình','Miền Bắc'],['Hải Phòng','Hải Phòng','Miền Bắc'],['Cát Bà','Hải Phòng','Miền Bắc'],
            ['Mai Châu','Hòa Bình','Miền Bắc'],['Tràng An','Ninh Bình','Miền Bắc'],['Mộc Châu','Sơn La','Miền Bắc'],
            ['Hà Giang','Hà Giang','Miền Bắc'],['Tam Đảo','Vĩnh Phúc','Miền Bắc'],['Yên Tử','Quảng Ninh','Miền Bắc'],
            ['Mù Cang Chải','Yên Bái','Miền Bắc'],['Cao Bằng','Cao Bằng','Miền Bắc'],['Điện Biên','Điện Biên','Miền Bắc'],
            ['Đà Nẵng','Đà Nẵng','Miền Trung'],['Hội An','Quảng Nam','Miền Trung'],['Huế','Thừa Thiên Huế','Miền Trung'],
            ['Bà Nà Hills','Đà Nẵng','Miền Trung'],['Quy Nhơn','Bình Định','Miền Trung'],['Phong Nha','Quảng Bình','Miền Trung'],
            ['Lý Sơn','Quảng Ngãi','Miền Trung'],['Nha Trang','Khánh Hòa','Miền Trung'],['Đà Lạt','Lâm Đồng','Miền Trung'],
            ['Phan Thiết','Bình Thuận','Miền Trung'],['Phú Yên','Phú Yên','Miền Trung'],['Ninh Thuận','Ninh Thuận','Miền Trung'],
            ['Pleiku','Gia Lai','Miền Trung'],['Buôn Ma Thuột','Đắk Lắk','Miền Trung'],['Kon Tum','Kon Tum','Miền Trung'],
            ['Mũi Né','Bình Thuận','Miền Trung'],['Tam Kỳ','Quảng Nam','Miền Trung'],['Bình Thuận','Bình Thuận','Miền Trung'],
            ['TP Hồ Chí Minh','TP HCM','Miền Nam'],['Phú Quốc','Kiên Giang','Miền Nam'],['Cần Thơ','Cần Thơ','Miền Nam'],
            ['Côn Đảo','Bà Rịa Vũng Tàu','Miền Nam'],['Vũng Tàu','Bà Rịa Vũng Tàu','Miền Nam'],['Châu Đốc','An Giang','Miền Nam'],
            ['Mỹ Tho','Tiền Giang','Miền Nam'],['Bến Tre','Bến Tre','Miền Nam'],['Long An','Long An','Miền Nam'],
            ['Tây Ninh','Tây Ninh','Miền Nam'],['Đồng Tháp','Đồng Tháp','Miền Nam'],['Rạch Giá','Kiên Giang','Miền Nam'],
            ['Cà Mau','Cà Mau','Miền Nam'],['Sóc Trăng','Sóc Trăng','Miền Nam'],['Bạc Liêu','Bạc Liêu','Miền Nam'],
            ['Lạng Sơn','Lạng Sơn','Miền Bắc'],['Tuyên Quang','Tuyên Quang','Miền Bắc'],
        ];
        $descs = ['Điểm đến hấp dẫn với cảnh quan thiên nhiên tuyệt đẹp và văn hóa đặc sắc.','Vùng đất giàu bản sắc với nhiều di tích lịch sử và danh lam thắng cảnh.','Thiên đường du lịch với khí hậu trong lành và con người thân thiện.','Điểm đến lý tưởng cho kỳ nghỉ với ẩm thực phong phú và phong cảnh hữu tình.','Nơi hội tụ tinh hoa văn hóa và vẻ đẹp thiên nhiên hoang sơ.'];
        $imgs = ['https://images.unsplash.com/photo-1528127269322-539801943592?w=800','https://images.unsplash.com/photo-1507525428034-b723cf961d3e?w=800','https://images.unsplash.com/photo-1508873696983-2df519f0397e?w=800','https://images.unsplash.com/photo-1501785888041-af3ef285b470?w=800','https://images.unsplash.com/photo-1469474968028-56623f02e42e?w=800','https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?w=800','https://images.unsplash.com/photo-1470071459604-3b5ec3a7fe05?w=800','https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?w=800'];

        $destIds = [];
        foreach ($destData as $i => $d) {
            $destIds[] = DB::table('destinations')->insertGetId([
                'name'=>$d[0],'location'=>$d[1],'region'=>$d[2],
                'description'=>$descs[$i % count($descs)].' [DEMO]',
                'image_path'=>$imgs[$i % count($imgs)],
                'created_at'=>$now,'updated_at'=>$now,
            ]);
        }
        echo "Created ".count($destIds)." destinations\n";

        // === 2. TOURS (100) ===
        $tourTemplates = [
            ['Khám phá %s %s','2 ngày 1 đêm',2500000,4500000],
            ['Tour %s trọn gói %s','3 ngày 2 đêm',3500000,6500000],
            ['%s - Hành trình di sản %s','4 ngày 3 đêm',5000000,9000000],
            ['Nghỉ dưỡng %s cao cấp %s','3 ngày 2 đêm',6000000,12000000],
            ['%s - Thiên đường biển %s','2 ngày 1 đêm',2000000,4000000],
            ['Phiêu lưu %s %s','1 ngày',1500000,3000000],
            ['%s - Văn hóa và ẩm thực %s','3 ngày 2 đêm',3000000,5500000],
            ['Trải nghiệm %s mùa đẹp nhất %s','4 ngày 3 đêm',4500000,8000000],
            ['Tour VIP %s %s','5 ngày 4 đêm',8000000,15000000],
            ['%s - Khám phá thiên nhiên %s','2 ngày 1 đêm',2200000,4000000],
        ];
        $transports = ['Máy bay','Du thuyền','Xe du lịch chất lượng cao'];
        $svcInc = "Xe đưa đón sân bay\nKhách sạn 3-5 sao\nBữa ăn theo chương trình\nHướng dẫn viên tiếng Việt\nVé tham quan\nBảo hiểm du lịch";
        $svcExc = "Chi phí cá nhân\nĐồ uống ngoài chương trình\nTip cho hướng dẫn viên\nPhụ thu phòng đơn";

        $tourIds = [];
        for ($i = 0; $i < 100; $i++) {
            $tpl = $tourTemplates[$i % count($tourTemplates)];
            $di = $i % count($destIds);
            $destName = $destData[$di][0];
            $suffix = $i >= 50 ? ' - Hè '.date('Y') : '';
            $title = sprintf($tpl[0], $destName, $suffix);
            $slug = 'demo-'.Str::slug($title).'-'.($i+1);
            $price = rand((int)($tpl[2]/100000), (int)($tpl[3]/100000)) * 100000;
            $tourIds[] = DB::table('tours')->insertGetId([
                'title'=>$title,'slug'=>$slug,'summary'=>"Tour du lịch {$destName} với lịch trình hấp dẫn, dịch vụ chất lượng cao.",
                'base_price'=>$price,'duration'=>$tpl[1],'transportation'=>$transports[$i%3],
                'service_includes'=>$svcInc,'service_excludes'=>$svcExc,'is_active'=>1,
                'itinerary'=>"<h3>Ngày 1</h3><p>Đón khách - Di chuyển đến {$destName}</p><h3>Ngày 2</h3><p>Tham quan các điểm nổi bật tại {$destName}</p>",
                'created_at'=>$now->copy()->subDays(rand(1,90)),'updated_at'=>$now,
            ]);
            // Pivot
            DB::table('tour_destinations')->insert(['tour_id'=>end($tourIds),'destination_id'=>$destIds[$di],'order_index'=>1]);
            if ($i % 3 == 0 && isset($destIds[($di+1)%count($destIds)])) {
                DB::table('tour_destinations')->insert(['tour_id'=>end($tourIds),'destination_id'=>$destIds[($di+1)%count($destIds)],'order_index'=>2]);
            }
            // Tour image
            DB::table('tour_images')->insert([
                'tour_id'=>end($tourIds),'image_path'=>$imgs[$i%count($imgs)],'is_primary'=>1,
                'created_at'=>$now,'updated_at'=>$now,
            ]);
        }
        echo "Created ".count($tourIds)." tours\n";

        // === 3. DEPARTURES (2-3 per tour) ===
        $depIds = [];
        foreach ($tourIds as $idx => $tid) {
            $numDeps = rand(2,3);
            for ($d=0; $d<$numDeps; $d++) {
                $seats = rand(20,45);
                $booked = rand(0, (int)($seats*0.4));
                $depIds[] = DB::table('departures')->insertGetId([
                    'tour_id'=>$tid,
                    'start_date'=>$now->copy()->addDays(rand(5,120)+$d*15),
                    'max_seats'=>$seats,'available_seats'=>$seats-$booked,
                    'price_override'=>null,
                    'created_at'=>$now,'updated_at'=>$now,
                ]);
            }
        }
        echo "Created ".count($depIds)." departures\n";

        // === 4. CUSTOMERS (200) ===
        $hoVN = ['Nguyễn','Trần','Lê','Phạm','Hoàng','Huỳnh','Phan','Vũ','Võ','Đặng','Bùi','Đỗ','Hồ','Ngô','Dương','Lý'];
        $demVN = ['Văn','Thị','Hoàng','Thanh','Minh','Đức','Quốc','Ngọc','Thu','Hồng','Công','Xuân','Hữu','Phương','Bảo'];
        $tenVN = ['An','Bình','Cường','Dũng','Em','Phúc','Giang','Hà','Hải','Khoa','Lan','Mai','Nam','Oanh','Phong','Quân','Sơn','Tâm','Uyên','Vinh','Yến','Đạt','Hưng','Linh','Thảo','Tuấn','Hiếu','Nhi','Trung','Hương'];
        $pwd = Hash::make('password123');
        $userIds = [];
        for ($i = 1; $i <= 200; $i++) {
            $fn = $hoVN[array_rand($hoVN)].' '.$demVN[array_rand($demVN)].' '.$tenVN[array_rand($tenVN)];
            $phone = '0'.rand(3,9).rand(10000000,99999999);
            $userIds[] = DB::table('users')->insertGetId([
                'role_id'=>2,'email'=>sprintf('demo.customer%03d@example.com',$i),
                'password'=>$pwd,'full_name'=>$fn,'phone'=>$phone,
                'status'=>1,'created_at'=>$now->copy()->subDays(rand(1,365)),'updated_at'=>$now,
            ]);
        }
        echo "Created ".count($userIds)." customers\n";

        // === 5. BOOKINGS (200) ===
        // Status distribution: 25 Pending, 55 Confirmed, 70 Paid(Confirmed+Paid), 25 Completed, 25 Cancelled
        $statuses = array_merge(
            array_fill(0,25,['Pending','Unpaid']),
            array_fill(0,55,['Confirmed','Unpaid']),
            array_fill(0,70,['Confirmed','Paid']),
            array_fill(0,25,['Completed','Paid']),
            array_fill(0,25,['Cancelled','Unpaid'])
        );
        shuffle($statuses);
        $methods = ['Cash','BankTransfer','Momo'];
        $bookingIds = [];
        $paidBookings = [];

        for ($i = 0; $i < 200; $i++) {
            $depId = $depIds[array_rand($depIds)];
            $dep = DB::table('departures')->find($depId);
            $tour = DB::table('tours')->find($dep->tour_id);
            $numPax = rand(1,4);
            $total = $tour->base_price * $numPax;
            $st = $statuses[$i];
            $createdAt = $now->copy()->subDays(rand(1,365));
            $code = sprintf('DEMO-BK-%04d', $i+1);
            $bid = DB::table('bookings')->insertGetId([
                'booking_code'=>$code,'user_id'=>$userIds[array_rand($userIds)],
                'departure_id'=>$depId,'total_price'=>$total,
                'status'=>$st[0],'payment_status'=>$st[1],
                'notes'=>null,'created_at'=>$createdAt,'updated_at'=>$now,
            ]);
            $bookingIds[] = $bid;
            // Passengers
            for ($p=0; $p<$numPax; $p++) {
                $pname = $hoVN[array_rand($hoVN)].' '.$demVN[array_rand($demVN)].' '.$tenVN[array_rand($tenVN)];
                DB::table('passengers')->insert([
                    'booking_id'=>$bid,'name'=>$pname,
                    'id_card'=>'0'.rand(10000000,99999999).''.rand(10,99),
                    'ticket_code'=>'TK-'.strtoupper(Str::random(8)),
                    'created_at'=>$createdAt,'updated_at'=>$now,
                ]);
            }
            if ($st[1] === 'Paid') {
                $paidBookings[] = ['id'=>$bid,'amount'=>$total,'date'=>$createdAt];
            }
        }
        echo "Created ".count($bookingIds)." bookings\n";

        // === 6. PAYMENTS (for paid bookings) + ensure 50+ for statistics ===
        $payCount = 0;
        foreach ($paidBookings as $pb) {
            DB::table('payments')->insert([
                'booking_id'=>$pb['id'],'method'=>$methods[array_rand($methods)],
                'amount'=>$pb['amount'],'transaction_id'=>'TXN-'.strtoupper(Str::random(10)),
                'payment_date'=>$pb['date'],'created_at'=>$pb['date'],'updated_at'=>$now,
            ]);
            $payCount++;
        }
        echo "Created {$payCount} payments\n";

        // === 7. REVIEWS (50) for statistics ===
        $comments = [
            'Chuyến đi tuyệt vời, hướng dẫn viên nhiệt tình!','Dịch vụ rất tốt, khách sạn sạch sẽ, sẽ quay lại.',
            'Lịch trình hợp lý, gia đình rất hài lòng.','Cảnh đẹp tuyệt vời, đồ ăn ngon, giá cả phải chăng.',
            'Tour rất chuyên nghiệp, xe mới, tài xế vui tính.','Trải nghiệm đáng nhớ, sẽ giới thiệu bạn bè.',
            'Rất đáng giá, phong cảnh đẹp ngoài mong đợi.','Nhân viên phục vụ chu đáo, hỗ trợ nhiệt tình.',
            'Chuyến đi hoàn hảo cho gia đình có trẻ nhỏ.','Sẽ đặt thêm tour khác trong tương lai.',
        ];
        for ($i = 0; $i < 50; $i++) {
            DB::table('reviews')->insert([
                'tour_id'=>$tourIds[array_rand($tourIds)],'user_id'=>$userIds[array_rand($userIds)],
                'rating'=>rand(3,5),'comment'=>$comments[array_rand($comments)],
                'is_hidden'=>0,'created_at'=>$now->copy()->subDays(rand(1,180)),'updated_at'=>$now,
            ]);
        }
        echo "Created 50 reviews\n";

        echo "\n=== DEMO SEED COMPLETE ===\n";
    }
}
