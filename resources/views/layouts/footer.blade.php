<footer style="background: #333; color: white; padding: 40px 0 20px; margin-top: 40px;">
    <div class="container">
        <!-- Mobile Footer Layout -->
        <div class="footer-content" style="display: grid; grid-template-columns: 1fr; gap: 24px;">
            <!-- Company Info -->
            <div class="footer-section">
                <h5 style="color: #ff6b6b; font-size: 1.2rem; margin-bottom: 12px;">Len Lab</h5>
                <p style="font-size: 0.9rem; line-height: 1.5; color: #ccc; margin-bottom: 16px;">
                    Cửa hàng đan móc thủ công chất lượng cao. Mang đến những sản phẩm len đẹp và bền vững.
                </p>
                <div class="social-links" style="display: flex; gap: 12px;">
                    <a href="#" style="color: white; font-size: 1.2rem; width: 36px; height: 36px; background: rgba(255, 107, 107, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s;">
                        <i class="fab fa-facebook"></i>
                    </a>
                    <a href="#" style="color: white; font-size: 1.2rem; width: 36px; height: 36px; background: rgba(255, 107, 107, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s;">
                        <i class="fab fa-instagram"></i>
                    </a>
                    <a href="#" style="color: white; font-size: 1.2rem; width: 36px; height: 36px; background: rgba(255, 107, 107, 0.2); border-radius: 50%; display: flex; align-items: center; justify-content: center; text-decoration: none; transition: all 0.3s;">
                        <i class="fab fa-tiktok"></i>
                    </a>
                </div>
            </div>
            
            <!-- Quick Links -->
            <div class="footer-section">
                <h5 style="color: #ff6b6b; font-size: 1.1rem; margin-bottom: 12px;">Liên kết nhanh</h5>
                <ul style="list-style: none; padding: 0; margin: 0;">
                    <li style="margin-bottom: 8px;">
                        <a href="/" style="color: #ccc; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">Trang chủ</a>
                    </li>
                    <li style="margin-bottom: 8px;">
                        <a href="/san-pham" style="color: #ccc; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">Sản phẩm</a>
                    </li>
                    <li style="margin-bottom: 8px;">
                        <a href="/gioi-thieu" style="color: #ccc; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">Giới thiệu</a>
                    </li>
                    <li style="margin-bottom: 8px;">
                        <a href="/cart" style="color: #ccc; text-decoration: none; font-size: 0.9rem; transition: color 0.3s;">Giỏ hàng</a>
                    </li>
                </ul>
            </div>
            
            <!-- Contact Info -->
            <div class="footer-section">
                <h5 style="color: #ff6b6b; font-size: 1.1rem; margin-bottom: 12px;">Liên hệ</h5>
                <div style="display: flex; flex-direction: column; gap: 8px;">
                    <p style="margin: 0; font-size: 0.9rem; color: #ccc; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-map-marker-alt" style="color: #ff6b6b; width: 16px;"></i>
                        123 Đường ABC, Quận XYZ, TP.HCM
                    </p>
                    <p style="margin: 0; font-size: 0.9rem; color: #ccc; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-phone" style="color: #ff6b6b; width: 16px;"></i>
                        <a href="tel:0123456789" style="color: #ccc; text-decoration: none;">0123 456 789</a>
                    </p>
                    <p style="margin: 0; font-size: 0.9rem; color: #ccc; display: flex; align-items: center; gap: 8px;">
                        <i class="fas fa-envelope" style="color: #ff6b6b; width: 16px;"></i>
                        <a href="mailto:info@lenlab.com" style="color: #ccc; text-decoration: none;">info@lenlab.com</a>
                    </p>
                </div>
            </div>
            
           
        </div>
        
        <hr style="border-color: #555; margin: 24px 0 16px;">
        
        <div class="text-center">
            <p style="margin: 0; font-size: 0.8rem; color: #999;">
                &copy; {{ date('Y') }} Len Lab. Tất cả quyền được bảo lưu.
            </p>
        </div>
    </div>
</footer>

<style>
    /* Footer Responsive Styles */
    .footer-content .social-links a:hover {
        background: rgba(255, 107, 107, 0.4) !important;
        transform: translateY(-2px);
    }
    
    .footer-section ul li a:hover {
        color: #ff6b6b !important;
    }
    
    /* Tablet Styles */
    @media (min-width: 768px) {
        footer {
            padding: 50px 0 30px !important;
        }
        
        .footer-content {
            grid-template-columns: repeat(2, 1fr) !important;
            gap: 32px !important;
        }
        
        .footer-section h5 {
            font-size: 1.2rem !important;
        }
        
        .footer-section p,
        .footer-section ul li a {
            font-size: 1rem !important;
        }
    }
    
    /* Desktop Styles */
    @media (min-width: 1024px) {
        footer {
            padding: 60px 0 40px !important;
        }
        
        .footer-content {
            grid-template-columns: 2fr 1fr 1fr 1fr !important;
            gap: 40px !important;
        }
        
        .footer-section:first-child {
            padding-right: 20px;
        }
        
        .footer-section h5 {
            font-size: 1.3rem !important;
            margin-bottom: 16px !important;
        }
        
        .footer-section p {
            font-size: 1rem !important;
            line-height: 1.6 !important;
        }
        
        .social-links {
            gap: 16px !important;
        }
        
        .social-links a {
            width: 40px !important;
            height: 40px !important;
            font-size: 1.3rem !important;
        }
    }
</style>