<?php

namespace Database\Seeders;

use Illuminate\Database\Seeder;
use Illuminate\Support\Facades\DB;
use Illuminate\Support\Facades\Hash;
use Illuminate\Support\Str;
use Carbon\Carbon;

class DatabaseSeeder extends Seeder
{
    /**
     * Seed the application's database.
     *
     * @return void
     */
    public function run()
    {
        // Disable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=0;');

        // Truncate tables
        $tables = [
            'roles', 'users', 'destinations', 'news_categories', 'tours', 
            'tour_destinations', 'departures', 'news', 'bookings', 
            'passengers', 'payments', 'promotions', 'reviews', 
            'chat_history', 'tour_images'
        ];
        foreach ($tables as $table) {
            DB::table($table)->truncate();
        }

        // Re-enable foreign key checks
        DB::statement('SET FOREIGN_KEY_CHECKS=1;');

        // 1. Roles
        DB::table('roles')->insert([
            ['id' => 1, 'role_name' => 'Admin', 'created_at' => now(), 'updated_at' => now()],
            ['id' => 2, 'role_name' => 'Customer', 'created_at' => now(), 'updated_at' => now()],
        ]);

        // 2. Users
        DB::table('users')->insert([
            [
                'id' => 1, 'role_id' => 1, 'email' => 'admin@admin.com', 
                'password' => Hash::make('admin123'), 'full_name' => 'Administrator', 
                'phone' => '0987654321', 'status' => 1, 'created_at' => now()
            ],
            [
                'id' => 2, 'role_id' => 2, 'email' => 'khachhang@gmail.com', 
                'password' => Hash::make('password123'), 'full_name' => 'Nguyễn Văn Khách', 
                'phone' => '0123456789', 'status' => 1, 'created_at' => now()
            ],
            [
                'id' => 3, 'role_id' => 2, 'email' => 'tranvanb@gmail.com', 
                'password' => Hash::make('password123'), 'full_name' => 'Trần Văn B', 
                'phone' => '0123444555', 'status' => 1, 'created_at' => now()
            ],
        ]);

        // 3. Destinations
        $destinations = [
            ['id' => 1, 'name' => 'Vịnh Hạ Long', 'location' => 'Quảng Ninh', 'description' => 'Di sản thiên nhiên thế giới UNESCO với hàng nghìn hòn đảo kỳ vĩ.', 'image_path' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=800&q=80'],
            ['id' => 2, 'name' => 'Phố Cổ Hội An', 'location' => 'Quảng Nam', 'description' => 'Nét đẹp hoài cổ giữa lòng miền Trung với đèn lồng rực rỡ.', 'image_path' => 'https://images.unsplash.com/photo-1599708149873-be7cc18b3c0b?auto=format&fit=crop&w=800&q=80'],
            ['id' => 3, 'name' => 'Đảo Ngọc Phú Quốc', 'location' => 'Kiên Giang', 'description' => 'Thiên đường nghỉ dưỡng biển xanh cát trắng, rạn san hô tuyệt đẹp.', 'image_path' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80'],
            ['id' => 4, 'name' => 'Sa Pa', 'location' => 'Lào Cai', 'description' => 'Thị trấn mờ sương với ruộng bậc thang hùng vĩ và bản sắc vùng cao.', 'image_path' => 'https://images.unsplash.com/photo-1508873696983-2df519f0397e?auto=format&fit=crop&w=800&q=80'],
            ['id' => 5, 'name' => 'Bà Nà Hills', 'location' => 'Đà Nẵng', 'description' => 'Đường lên tiên cảnh với Cầu Vàng nổi tiếng thế giới.', 'image_path' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80'],
            ['id' => 6, 'name' => 'Nha Trang', 'location' => 'Khánh Hòa', 'description' => 'Vịnh biển đẹp nhất thế giới với các hoạt động giải trí biển sôi động.', 'image_path' => 'https://images.unsplash.com/photo-1582972236019-ea4af5faf521?auto=format&fit=crop&w=800&q=80'],
            ['id' => 7, 'name' => 'Đà Lạt', 'location' => 'Lâm Đồng', 'description' => 'Thành phố ngàn hoa với khí hậu mát mẻ quanh năm và đồi thông thơ mộng.', 'image_path' => 'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?auto=format&fit=crop&w=800&q=80'],
            ['id' => 8, 'name' => 'Ninh Bình', 'location' => 'Ninh Bình', 'description' => 'Vịnh Hạ Long trên cạn với quần thể danh thắng Tràng An, Tam Cốc.', 'image_path' => 'https://images.unsplash.com/photo-1534008897995-27a23e859048?auto=format&fit=crop&w=800&q=80'],
        ];
        foreach ($destinations as $dest) {
            DB::table('destinations')->insert(array_merge($dest, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 4. News Categories
        DB::table('news_categories')->insert([
            ['id' => 1, 'name' => 'Cẩm nang du lịch', 'slug' => 'cam-nang-du-lich', 'created_at' => now()],
            ['id' => 2, 'name' => 'Tin khuyến mãi', 'slug' => 'tin-khuyen-mai', 'created_at' => now()],
            ['id' => 3, 'name' => 'Kinh nghiệm thực tế', 'slug' => 'kinh-nghiem-thuc-te', 'created_at' => now()],
        ]);

        // 5. Tours
        $tours = [
            [
                'id' => 1, 'title' => 'Tour Hạ Long 2 ngày 1 đêm - Du thuyền 5 sao', 
                'slug' => 'tour-ha-long-2-ngay-1-dem', 'base_price' => 3500000, 
                'duration' => '2 ngày 1 đêm', 'is_active' => 1, 'summary' => 'Trải nghiệm ngủ đêm trên vịnh, chèo thuyền kayak và ngắm hoàng hôn tuyệt đẹp.'
            ],
            [
                'id' => 2, 'title' => 'Khám phá Đà Nẵng - Hội An - Bà Nà Hills', 
                'slug' => 'kham-pha-da-nang-hoi-an', 'base_price' => 4200000, 
                'duration' => '3 ngày 2 đêm', 'is_active' => 1, 'summary' => 'Hành trình di sản miền Trung kết hợp vui chơi giải trí đỉnh cao tại Bà Nà.'
            ],
            [
                'id' => 3, 'title' => 'Sapa - Chinh phục đỉnh Fansipan', 
                'slug' => 'sapa-fansipan', 'base_price' => 2800000, 
                'duration' => '2 ngày 3 đêm', 'is_active' => 1, 'summary' => 'Trải nghiệm cáp treo kỷ lục, ngắm thung lũng Mường Hoa và săn mây trên đỉnh thiêng.'
            ],
            [
                'id' => 4, 'title' => 'Đảo Ngọc Phú Quốc - Thiên đường biển xanh', 
                'slug' => 'dao-ngoc-phu-quoc', 'base_price' => 5500000, 
                'duration' => '3 ngày 2 đêm', 'is_active' => 1, 'summary' => 'Khám phá các hòn đảo hoang sơ, lặn ngắm san hô và thưởng thức hải sản tươi sống.'
            ],
            [
                'id' => 5, 'title' => 'Nha Trang - Lặn ngắm san hô đảo Tứ Bình', 
                'slug' => 'nha-trang-tu-binh', 'base_price' => 3900000, 
                'duration' => '3 ngày 2 đêm', 'is_active' => 1, 'summary' => 'Tận hưởng biển xanh cát trắng, tham quan tháp bà Ponagar và tắm bùn khoáng nóng.'
            ],
            [
                'id' => 6, 'title' => 'Đà Lạt - Thành phố ngàn hoa 3 ngày 2 đêm', 
                'slug' => 'da-lat-ngan-hoa', 'base_price' => 3200000, 
                'duration' => '3 ngày 2 đêm', 'is_active' => 1, 'summary' => 'Thư giãn giữa không gian se lạnh, tham quan đồi chè Cầu Đất và săn mây sáng sớm.'
            ],
            [
                'id' => 7, 'title' => 'Tràng An - Bái Đính - Hang Múa Ninh Bình', 
                'slug' => 'trang-an-bai-dinh', 'base_price' => 1800000, 
                'duration' => '1 ngày', 'is_active' => 1, 'summary' => 'Hành trình tâm linh và ngắm nhìn bức tranh sơn thủy hữu tình từ đỉnh Hang Múa.'
            ],
            [
                'id' => 8, 'title' => 'Xuyên Việt: Hà Nội - Huế - Đà Nẵng - Sài Gòn', 
                'slug' => 'xuyen-viet-ha-noi-sai-gon', 'base_price' => 12500000, 
                'duration' => '7 ngày 6 đêm', 'is_active' => 1, 'summary' => 'Chuyến đi để đời khám phá trọn vẹn tinh hoa văn hóa, ẩm thực và cảnh quan Việt Nam.'
            ],
        ];
        foreach ($tours as $tour) {
            DB::table('tours')->insert(array_merge($tour, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 6. Tour Destinations
        DB::table('tour_destinations')->insert([
            ['tour_id' => 1, 'destination_id' => 1, 'order_index' => 1],
            ['tour_id' => 2, 'destination_id' => 2, 'order_index' => 1],
            ['tour_id' => 2, 'destination_id' => 5, 'order_index' => 2],
            ['tour_id' => 3, 'destination_id' => 4, 'order_index' => 1],
            ['tour_id' => 4, 'destination_id' => 3, 'order_index' => 1],
            ['tour_id' => 5, 'destination_id' => 6, 'order_index' => 1],
            ['tour_id' => 6, 'destination_id' => 7, 'order_index' => 1],
            ['tour_id' => 7, 'destination_id' => 8, 'order_index' => 1],
            ['tour_id' => 8, 'destination_id' => 1, 'order_index' => 1],
            ['tour_id' => 8, 'destination_id' => 2, 'order_index' => 2],
            ['tour_id' => 8, 'destination_id' => 5, 'order_index' => 3],
        ]);

        // 7. Departures
        $departures = [
            ['id' => 1, 'tour_id' => 1, 'start_date' => Carbon::now()->addDays(5), 'max_seats' => 30, 'available_seats' => 25],
            ['id' => 2, 'tour_id' => 1, 'start_date' => Carbon::now()->addDays(12), 'max_seats' => 30, 'available_seats' => 30],
            ['id' => 3, 'tour_id' => 2, 'start_date' => Carbon::now()->addDays(8), 'max_seats' => 40, 'available_seats' => 38],
            ['id' => 4, 'tour_id' => 2, 'start_date' => Carbon::now()->addDays(18), 'max_seats' => 40, 'available_seats' => 40],
            ['id' => 5, 'tour_id' => 3, 'start_date' => Carbon::now()->addDays(3), 'max_seats' => 20, 'available_seats' => 15],
            ['id' => 6, 'tour_id' => 4, 'start_date' => Carbon::now()->addDays(10), 'max_seats' => 35, 'available_seats' => 30],
            ['id' => 7, 'tour_id' => 5, 'start_date' => Carbon::now()->addDays(14), 'max_seats' => 25, 'available_seats' => 20],
            ['id' => 8, 'tour_id' => 6, 'start_date' => Carbon::now()->addDays(7), 'max_seats' => 30, 'available_seats' => 28],
            ['id' => 9, 'tour_id' => 7, 'start_date' => Carbon::now()->addDays(4), 'max_seats' => 45, 'available_seats' => 40],
            ['id' => 10, 'tour_id' => 8, 'start_date' => Carbon::now()->addDays(20), 'max_seats' => 20, 'available_seats' => 18],
        ];
        foreach ($departures as $dep) {
            DB::table('departures')->insert(array_merge($dep, ['created_at' => now(), 'updated_at' => now()]));
        }

        // 8. News
        $news = [
            [
                'id' => 1, 'category_id' => 1, 'author_id' => 1, 
                'title' => 'Top 10 địa điểm không thể bỏ qua tại Hội An năm 2026', 
                'slug' => 'top-10-dia-diem-hoi-an-2026', 
                'content' => 'Hội An luôn mang một vẻ đẹp quyến rũ đặc biệt. Dưới đây là danh sách 10 điểm check-in ấn tượng nhất do các chuyên gia của Tour Travel tổng hợp, từ chùa Cầu cổ kính đến những quán cafe tầng thượng ngắm hoàng hôn sông Thu Bồn...', 
                'image_path' => 'https://images.unsplash.com/photo-1599708149873-be7cc18b3c0b?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(2)
            ],
            [
                'id' => 2, 'category_id' => 2, 'author_id' => 1, 
                'title' => 'Tưng bừng khuyến mãi hè: Giảm đến 20% cho tour gia đình', 
                'slug' => 'khuyen-mai-he-tour-gia-dinh', 
                'content' => 'Chào đón mùa hè sôi động, Tour Travel triển khai chương trình ưu đãi đặc biệt dành cho các gia đình đăng ký tour sớm. Khách hàng sẽ nhận được mã giảm giá trực tiếp và các phần quà tặng hấp dẫn cho bé...', 
                'image_path' => 'https://images.unsplash.com/photo-1502082553048-f009c37129b9?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(4)
            ],
            [
                'id' => 3, 'category_id' => 3, 'author_id' => 1, 
                'title' => 'Bí kíp săn mây thành công tại đỉnh Fansipan - Sa Pa', 
                'slug' => 'bi-kip-san-may-fansipan', 
                'content' => 'Săn mây là một nghệ thuật và người săn mây cần nắm rõ thời tiết. Hãy cùng Tour Travel tìm hiểu khung giờ vàng và những trang bị cần thiết để có những bức ảnh để đời trên Nóc nhà Đông Dương...', 
                'image_path' => 'https://images.unsplash.com/photo-1508873696983-2df519f0397e?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(6)
            ],
            [
                'id' => 4, 'category_id' => 1, 'author_id' => 1, 
                'title' => 'Hành trình khám phá ẩm thực đường phố Đà Nẵng', 
                'slug' => 'am-thuc-duong-pho-da-nang', 
                'content' => 'Đà Nẵng không chỉ nổi tiếng với biển đẹp mà còn là thiên đường ẩm thực. Bánh xép, mì Quảng, bún chả cá và nem lụi là những món ăn bạn nhất định phải thử khi đặt chân đến thành phố đáng sống này cùng Tour Travel...', 
                'image_path' => 'https://images.unsplash.com/photo-1555939594-58d7cb561ad1?auto=format&fit=crop&w=800&q=80',
                'created_at' => now()->subDays(10)
            ],
        ];
        foreach ($news as $item) {
            DB::table('news')->insert(array_merge($item, ['updated_at' => now()]));
        }

        // 9. Bookings
        DB::table('bookings')->insert([
            [
                'id' => 1, 'booking_code' => 'BK12345678', 'user_id' => 2, 
                'departure_id' => 1, 'total_price' => 17500000, 
                'status' => 'Confirmed', 'payment_status' => 'Paid', 'created_at' => now()->subDays(5)
            ],
            [
                'id' => 2, 'booking_code' => 'BK87654321', 'user_id' => 3, 
                'departure_id' => 5, 'total_price' => 8400000, 
                'status' => 'Pending', 'payment_status' => 'Unpaid', 'created_at' => now()->subDays(2)
            ],
            [
                'id' => 3, 'booking_code' => 'BK55566677', 'user_id' => 2, 
                'departure_id' => 6, 'total_price' => 16500000, 
                'status' => 'Completed', 'payment_status' => 'Paid', 'created_at' => now()->subDays(15)
            ],
            [
                'id' => 4, 'booking_code' => 'BK99988877', 'user_id' => 3, 
                'departure_id' => 3, 'total_price' => 12600000, 
                'status' => 'Completed', 'payment_status' => 'Paid', 'created_at' => now()->subDays(20)
            ],
        ]);

        // 10. Passengers
        DB::table('passengers')->insert([
            ['booking_id' => 1, 'name' => 'Nguyễn Văn Khách', 'id_card' => '123456789'],
            ['booking_id' => 1, 'name' => 'Lê Thị Vợ', 'id_card' => '987654321'],
            ['booking_id' => 2, 'name' => 'Trần Văn B', 'id_card' => '111222333'],
            ['booking_id' => 3, 'name' => 'Nguyễn Văn Khách', 'id_card' => '123456789'],
            ['booking_id' => 4, 'name' => 'Trần Văn B', 'id_card' => '111222333'],
        ]);

        // 11. Payments
        DB::table('payments')->insert([
            ['booking_id' => 1, 'method' => 'Cash', 'amount' => 17500000, 'payment_date' => now()->subDays(5), 'created_at' => now()->subDays(5)],
            ['booking_id' => 3, 'method' => 'BankTransfer', 'amount' => 16500000, 'payment_date' => now()->subDays(15), 'created_at' => now()->subDays(15)],
            ['booking_id' => 4, 'method' => 'Momo', 'amount' => 12600000, 'payment_date' => now()->subDays(20), 'created_at' => now()->subDays(20)],
        ]);

        // 12. Promotions
        DB::table('promotions')->insert([
            ['code' => 'HELLO2026', 'discount_value' => 10, 'discount_type' => 'Percentage', 'expiry_date' => '2026-12-31', 'usage_limit' => 100, 'created_at' => now()],
            ['code' => 'SUMMER500', 'discount_value' => 500000, 'discount_type' => 'Fixed', 'expiry_date' => '2026-08-31', 'usage_limit' => 50, 'created_at' => now()],
            ['code' => 'VIPTOUR', 'discount_value' => 15, 'discount_type' => 'Percentage', 'expiry_date' => '2026-10-31', 'usage_limit' => 20, 'created_at' => now()],
        ]);

        // 13. Reviews
        DB::table('reviews')->insert([
            ['tour_id' => 1, 'user_id' => 2, 'rating' => 5, 'comment' => 'Chuyến đi tuyệt vời! Hướng dẫn viên rất nhiệt tình và chu đáo. Du thuyền sang trọng, đồ ăn ngon.', 'created_at' => now()->subDays(3)],
            ['tour_id' => 2, 'user_id' => 3, 'rating' => 5, 'comment' => 'Lịch trình rất hợp lý, gia đình tôi đã có những trải nghiệm đáng nhớ tại Bà Nà và Hội An.', 'created_at' => now()->subDays(10)],
            ['tour_id' => 4, 'user_id' => 2, 'rating' => 4, 'comment' => 'Biển Phú Quốc cực kỳ trong xanh. Khách sạn sạch sẽ, gần biển, dịch vụ của công ty Tour Travel rất tốt.', 'created_at' => now()->subDays(12)],
            ['tour_id' => 6, 'user_id' => 3, 'rating' => 5, 'comment' => 'Đà Lạt mùa này đẹp tuyệt vời. Công ty tổ chức chuyên nghiệp, xe di chuyển êm ái.', 'created_at' => now()->subDays(18)],
        ]);

        // 14. Chat History
        DB::table('chat_history')->insert([
            ['user_id' => 2, 'message' => 'Tour Hạ Long còn chỗ không?', 'reply' => 'Chào bạn, tour vẫn còn chỗ vào ngày 15/4 nhé.', 'created_at' => now()->subDays(4)],
            ['user_id' => 3, 'message' => 'Giá tour Đà Nẵng đã bao gồm vé cáp treo chưa?', 'reply' => 'Chào bạn, giá tour của Tour Travel đã trọn gói bao gồm vé cáp treo Bà Nà Hills rồi ạ.', 'created_at' => now()->subDays(1)],
        ]);
        
        // 15. Tour Images
        DB::table('tour_images')->insert([
            ['tour_id' => 1, 'image_path' => 'https://images.unsplash.com/photo-1528127269322-539801943592?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 1, 'image_path' => 'https://images.unsplash.com/photo-1552465011-b4e21bf6e79a?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 2, 'image_path' => 'https://images.unsplash.com/photo-1571896349842-33c89424de2d?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 2, 'image_path' => 'https://images.unsplash.com/photo-1599708149873-be7cc18b3c0b?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 3, 'image_path' => 'https://images.unsplash.com/photo-1508873696983-2df519f0397e?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 3, 'image_path' => 'https://images.unsplash.com/photo-1544644181-1484b3fdfc62?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 4, 'image_path' => 'https://images.unsplash.com/photo-1507525428034-b723cf961d3e?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 4, 'image_path' => 'https://images.unsplash.com/photo-1540206395-68808572332f?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 5, 'image_path' => 'https://images.unsplash.com/photo-1582972236019-ea4af5faf521?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 5, 'image_path' => 'https://images.unsplash.com/photo-1544735716-392fe2489ffa?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 6, 'image_path' => 'https://images.unsplash.com/photo-1504609773096-104ff2c73ba4?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 6, 'image_path' => 'https://images.unsplash.com/photo-1447752875215-b2761acb3c5d?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 7, 'image_path' => 'https://images.unsplash.com/photo-1534008897995-27a23e859048?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 7, 'image_path' => 'https://images.unsplash.com/photo-1527004013197-933c4bb611b3?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
            
            ['tour_id' => 8, 'image_path' => 'https://images.unsplash.com/photo-1555921015-5532091f6006?auto=format&fit=crop&w=800&q=80', 'is_primary' => 1],
            ['tour_id' => 8, 'image_path' => 'https://images.unsplash.com/photo-1569154941061-e231b4725ef1?auto=format&fit=crop&w=800&q=80', 'is_primary' => 0],
        ]);
    }
}
