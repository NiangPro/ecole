<!-- Navigation Ultra Moderne -->
<style>
    /* Navbar Styles Ultra Moderne */
    .navbar-modern {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        width: 100%;
        z-index: 9999;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.85) 0%, rgba(30, 41, 59, 0.9) 100%);
        backdrop-filter: blur(30px) saturate(180%);
        -webkit-backdrop-filter: blur(30px) saturate(180%);
        border-bottom: 2px solid rgba(6, 182, 212, 0.2);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 4px 20px rgba(0, 0, 0, 0.1);
    }
    
    .navbar-modern::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 2px;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.5), rgba(20, 184, 166, 0.5), rgba(6, 182, 212, 0.5), transparent);
        background-size: 200% 100%;
        animation: shimmer-top 3s linear infinite;
    }
    
    @keyframes shimmer-top {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .navbar-modern.scrolled {
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.95) 0%, rgba(30, 41, 59, 0.98) 100%);
        box-shadow: 0 10px 50px rgba(0, 0, 0, 0.3), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
        border-bottom-color: rgba(6, 182, 212, 0.4);
    }
    
    .navbar-container {
        max-width: 1200px;
        margin: 0 auto;
        padding: 0 20px;
        display: flex;
        align-items: center;
        justify-content: space-between;
        height: 60px;
    }
    
    /* Logo */
    .navbar-logo {
        display: flex;
        align-items: center;
        gap: 12px;
        text-decoration: none;
        transition: transform 0.3s ease;
    }
    
    .navbar-logo:hover {
        transform: scale(1.05);
    }
    
    .logo-image {
        width: 36px;
        height: 36px;
        border-radius: 8px;
        filter: drop-shadow(0 0 10px rgba(6, 182, 212, 0.5));
    }
    
    .logo-text {
        font-family: 'Orbitron', sans-serif;
        font-size: 0.95rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
    }
    
    /* Desktop Menu */
    .navbar-menu {
        display: flex;
        align-items: center;
        gap: 8px;
        list-style: none;
        margin: 0;
        padding: 0;
    }
    
    .navbar-item {
        position: relative;
    }
    
    .navbar-link {
        display: flex;
        align-items: center;
        gap: 5px;
        padding: 7px 12px;
        color: rgba(255, 255, 255, 0.9);
        text-decoration: none;
        font-size: 0.8rem;
        font-weight: 600;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        position: relative;
        overflow: hidden;
    }
    
    .navbar-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.15), transparent);
        transition: left 0.5s ease;
    }
    
    .navbar-link:hover::before {
        left: 100%;
    }
    
    .navbar-link:hover {
        background: rgba(6, 182, 212, 0.12);
        color: #06b6d4;
        transform: translateY(-2px);
        box-shadow: 0 4px 12px rgba(6, 182, 212, 0.2);
    }
    
    .navbar-link.active {
        background: linear-gradient(135deg, rgba(6, 182, 212, 0.2), rgba(20, 184, 166, 0.15));
        color: #06b6d4;
        box-shadow: 0 2px 8px rgba(6, 182, 212, 0.3);
        border: 1px solid rgba(6, 182, 212, 0.3);
    }
    
    .navbar-link.active::after {
        content: '';
        position: absolute;
        bottom: 0;
        left: 50%;
        transform: translateX(-50%);
        width: 60%;
        height: 2px;
        background: linear-gradient(90deg, transparent, #06b6d4, transparent);
        animation: pulse-line 2s ease-in-out infinite;
    }
    
    @keyframes pulse-line {
        0%, 100% { opacity: 0.5; width: 60%; }
        50% { opacity: 1; width: 80%; }
    }
    
    /* Dropdown */
    .dropdown {
        position: relative;
    }
    
    .dropdown-toggle {
        cursor: pointer;
    }
    
    .dropdown-icon {
        font-size: 0.65rem;
        transition: transform 0.3s ease;
    }
    
    .dropdown:hover .dropdown-icon {
        transform: rotate(180deg);
    }
    
    .dropdown-menu {
        position: absolute;
        top: calc(100% + 15px);
        left: 0;
        min-width: 260px;
        background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.95) 100%);
        backdrop-filter: blur(30px) saturate(180%);
        -webkit-backdrop-filter: blur(30px) saturate(180%);
        border: 2px solid rgba(6, 182, 212, 0.25);
        border-radius: 16px;
        padding: 8px;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-15px) scale(0.95);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: 0 25px 70px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
        overflow: hidden;
    }
    
    .dropdown-menu::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        animation: shimmer-border 3s linear infinite;
    }
    
    .dropdown:hover .dropdown-menu {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    
    .dropdown-item {
        display: flex;
        align-items: center;
        gap: 8px;
        padding: 8px 12px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 10px;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        font-size: 0.85rem;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .dropdown-item::before {
        content: '';
        position: absolute;
        left: 0;
        top: 0;
        bottom: 0;
        width: 3px;
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        transform: scaleY(0);
        transition: transform 0.3s ease;
    }
    
    .dropdown-item:hover {
        background: rgba(6, 182, 212, 0.12);
        color: #06b6d4;
        transform: translateX(6px);
        border-color: rgba(6, 182, 212, 0.3);
        box-shadow: 0 3px 10px rgba(6, 182, 212, 0.15);
    }
    
    .dropdown-item:hover::before {
        transform: scaleY(1);
    }
    
    .dropdown-item-icon {
        width: 28px;
        height: 28px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 6px;
        font-size: 1rem;
        transition: transform 0.3s ease;
        flex-shrink: 0;
    }
    
    .dropdown-item:hover .dropdown-item-icon {
        transform: scale(1.1);
    }
    
    .dropdown-item-content {
        flex: 1;
    }
    
    .dropdown-item-title {
        font-weight: 600;
        font-size: 0.85rem;
        margin-bottom: 1px;
        line-height: 1.3;
    }
    
    .dropdown-item-desc {
        font-size: 0.7rem;
        color: rgba(255, 255, 255, 0.5);
        line-height: 1.2;
    }
    
    .dropdown-divider {
        height: 1px;
        background: rgba(6, 182, 212, 0.2);
        margin: 8px 0;
        border: none;
    }
    
    .dropdown-item.danger {
        color: #ef4444;
    }
    
    .dropdown-item.danger:hover {
        background: rgba(239, 68, 68, 0.15);
        color: #ef4444;
    }
    
    /* CTA Button */
    .navbar-cta {
        padding: 6px 10px;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        color: #000;
        text-decoration: none;
        font-weight: 600;
        border-radius: 8px;
        font-size: 0.75rem;
        transition: all 0.3s cubic-bezier(0.4, 0, 0.2, 1);
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 4px;
        border: 2px solid transparent;
        position: relative;
        overflow: hidden;
        box-shadow: 0 3px 12px rgba(6, 182, 212, 0.25);
        min-width: 36px;
        height: 36px;
    }
    
    .navbar-cta::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(255, 255, 255, 0.2), transparent);
        transition: left 0.5s ease;
    }
    
    .navbar-cta:hover::before {
        left: 100%;
    }
    
    .navbar-cta:hover {
        transform: translateY(-3px) scale(1.05);
        box-shadow: 0 12px 35px rgba(6, 182, 212, 0.5);
        border-color: rgba(6, 182, 212, 0.5);
    }
    
    /* Notification Bell dans la navbar */
    .navbar-notification-bell {
        position: relative;
    }
    
    .navbar-notification-bell:hover {
        background: rgba(6, 182, 212, 0.15) !important;
        color: #06b6d4 !important;
        transform: scale(1.1);
    }
    
    .navbar-notification-badge {
        animation: pulse 2s infinite;
    }
    
    .navbar-notification-dropdown.active {
        display: flex !important;
        transform: translateY(0) !important;
        opacity: 1 !important;
    }
    
    body.dark-mode .navbar-notification-dropdown {
        background: #1e293b !important;
        border: 1px solid rgba(6, 182, 212, 0.3) !important;
    }
    
    /* Search Icon & Form */
    .navbar-search-icon {
        display: flex;
        align-items: center;
        justify-content: center;
        width: 36px;
        height: 36px;
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        border-radius: 50%;
        color: #06b6d4;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-right: 12px;
        font-size: 0.9rem;
    }
    
    .navbar-search-icon:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: scale(1.1);
    }
    
    .navbar-search-form {
        position: absolute;
        top: 100%;
        right: 20px;
        margin-top: 10px;
        width: 600px;
        max-width: calc(100vw - 40px);
        background: linear-gradient(135deg, rgba(51, 65, 85, 0.95) 0%, rgba(71, 85, 105, 0.9) 100%);
        backdrop-filter: blur(30px);
        border: 2px solid rgba(6, 182, 212, 0.3);
        border-radius: 20px;
        padding: 30px;
        box-shadow: 0 20px 60px rgba(0, 0, 0, 0.6), 0 0 0 1px rgba(6, 182, 212, 0.1) inset;
        opacity: 0;
        visibility: hidden;
        transform: translateY(-10px) scale(0.95);
        transition: all 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        z-index: 10000;
        overflow: hidden;
    }
    
    .navbar-search-form::before {
        content: '';
        position: absolute;
        top: 0;
        left: 0;
        right: 0;
        height: 3px;
        background: linear-gradient(90deg, #06b6d4, #14b8a6, #06b6d4);
        background-size: 200% 100%;
        animation: shimmer-border 3s linear infinite;
    }
    
    @keyframes shimmer-border {
        0% { background-position: 200% 0; }
        100% { background-position: -200% 0; }
    }
    
    .navbar-search-form.active {
        opacity: 1;
        visibility: visible;
        transform: translateY(0) scale(1);
    }
    
    .search-header {
        display: flex;
        align-items: center;
        justify-content: space-between;
        margin-bottom: 20px;
        padding-bottom: 15px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
    }
    
    .search-title {
        font-family: 'Poppins', sans-serif;
        font-size: 1.2rem;
        font-weight: 700;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
        background-clip: text;
        display: flex;
        align-items: center;
        gap: 10px;
    }
    
    .search-close {
        background: rgba(6, 182, 212, 0.1);
        border: 1px solid rgba(6, 182, 212, 0.3);
        color: #06b6d4;
        cursor: pointer;
        padding: 8px;
        width: 36px;
        height: 36px;
        display: flex;
        align-items: center;
        justify-content: center;
        border-radius: 10px;
        transition: all 0.3s ease;
    }
    
    .search-close:hover {
        background: rgba(6, 182, 212, 0.2);
        border-color: rgba(6, 182, 212, 0.5);
        transform: rotate(90deg) scale(1.1);
    }
    
    .search-wrapper {
        position: relative;
        display: flex;
        align-items: center;
        gap: 12px;
    }
    
    .search-input {
        flex: 1;
        padding: 16px 20px;
        background: rgba(6, 182, 212, 0.08);
        border: 2px solid rgba(6, 182, 212, 0.2);
        border-radius: 14px;
        color: #fff;
        font-size: 1rem;
        font-family: 'Inter', sans-serif;
        transition: all 0.3s ease;
        outline: none;
    }
    
    .search-input:focus {
        background: rgba(6, 182, 212, 0.12);
        border-color: rgba(6, 182, 212, 0.6);
        box-shadow: 0 0 0 4px rgba(6, 182, 212, 0.1), 0 0 30px rgba(6, 182, 212, 0.3);
        transform: translateY(-2px);
    }
    
    .search-input::placeholder {
        color: rgba(255, 255, 255, 0.4);
    }
    
    .search-button {
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        border: none;
        color: #000;
        cursor: pointer;
        padding: 16px 28px;
        border-radius: 14px;
        font-weight: 700;
        font-size: 0.95rem;
        font-family: 'Poppins', sans-serif;
        display: flex;
        align-items: center;
        justify-content: center;
        gap: 8px;
        transition: all 0.3s ease;
        box-shadow: 0 4px 15px rgba(6, 182, 212, 0.3);
        white-space: nowrap;
    }
    
    .search-button:hover {
        transform: translateY(-2px);
        box-shadow: 0 8px 25px rgba(6, 182, 212, 0.5);
    }
    
    .search-button:active {
        transform: translateY(0);
    }
    
    .search-hint {
        margin-top: 15px;
        padding: 12px;
        background: rgba(6, 182, 212, 0.05);
        border-left: 3px solid rgba(6, 182, 212, 0.5);
        border-radius: 8px;
        font-size: 0.85rem;
        color: rgba(255, 255, 255, 0.6);
        display: flex;
        align-items: center;
        gap: 8px;
    }
    
    /* Mobile Menu Toggle */
    .mobile-toggle {
        display: none;
        flex-direction: column;
        gap: 5px;
        background: none;
        border: none;
        cursor: pointer;
        padding: 8px;
    }
    
    .mobile-toggle-line {
        width: 25px;
        height: 2px;
        background: #06b6d4;
        border-radius: 2px;
        transition: all 0.3s ease;
    }
    
    .mobile-toggle.active .mobile-toggle-line:nth-child(1) {
        transform: rotate(45deg) translateY(7px);
    }
    
    .mobile-toggle.active .mobile-toggle-line:nth-child(2) {
        opacity: 0;
    }
    
    .mobile-toggle.active .mobile-toggle-line:nth-child(3) {
        transform: rotate(-45deg) translateY(-7px);
    }
    
    /* Mobile Menu Overlay */
    .mobile-menu-overlay {
        position: fixed;
        top: 0;
        left: 0;
        right: 0;
        bottom: 0;
        background: rgba(0, 0, 0, 0.8);
        backdrop-filter: blur(10px);
        z-index: 9998;
        opacity: 0;
        visibility: hidden;
        transition: all 0.3s ease;
    }
    
    .mobile-menu-overlay.active {
        opacity: 1;
        visibility: visible;
    }
    
    /* Mobile Menu */
    .mobile-menu {
        position: fixed;
        top: 0;
        right: -100%;
        width: 85%;
        max-width: 400px;
        height: 100vh;
        background: linear-gradient(180deg, rgba(51, 65, 85, 0.95) 0%, rgba(71, 85, 105, 0.95) 100%);
        backdrop-filter: blur(30px);
        border-left: 2px solid rgba(6, 182, 212, 0.3);
        z-index: 9999;
        overflow-y: auto;
        transition: right 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        box-shadow: -10px 0 50px rgba(0, 0, 0, 0.5);
    }
    
    .mobile-menu.active {
        right: 0;
    }
    
    .mobile-menu::-webkit-scrollbar {
        width: 6px;
    }
    
    .mobile-menu::-webkit-scrollbar-thumb {
        background: linear-gradient(180deg, #06b6d4, #14b8a6);
        border-radius: 10px;
    }
    
    .mobile-menu-header {
        padding: 25px 20px;
        border-bottom: 1px solid rgba(6, 182, 212, 0.2);
        display: flex;
        align-items: center;
        justify-content: space-between;
        background: rgba(6, 182, 212, 0.05);
    }
    
    .mobile-menu-title {
        font-size: 1.3rem;
        font-weight: 800;
        background: linear-gradient(135deg, #06b6d4, #14b8a6);
        -webkit-background-clip: text;
        -webkit-text-fill-color: transparent;
    }
    
    .mobile-menu-close {
        width: 40px;
        height: 40px;
        border-radius: 10px;
        background: rgba(6, 182, 212, 0.1);
        border: none;
        color: #06b6d4;
        font-size: 1.2rem;
        cursor: pointer;
        display: flex;
        align-items: center;
        justify-content: center;
        transition: all 0.3s ease;
    }
    
    .mobile-menu-close:hover {
        background: rgba(6, 182, 212, 0.2);
        transform: rotate(90deg);
    }
    
    .mobile-menu-list {
        list-style: none;
        padding: 20px;
        margin: 0;
    }
    
    .mobile-menu-item {
        margin-bottom: 10px;
    }
    
    .mobile-menu-link {
        display: flex;
        align-items: center;
        gap: 15px;
        padding: 16px 18px;
        color: rgba(255, 255, 255, 0.95);
        text-decoration: none;
        border-radius: 12px;
        font-weight: 600;
        font-size: 1rem;
        transition: all 0.3s ease;
        border: 1px solid transparent;
        position: relative;
        overflow: hidden;
    }
    
    .mobile-menu-link::before {
        content: '';
        position: absolute;
        top: 0;
        left: -100%;
        width: 100%;
        height: 100%;
        background: linear-gradient(90deg, transparent, rgba(6, 182, 212, 0.1), transparent);
        transition: left 0.5s ease;
    }
    
    .mobile-menu-link:hover::before {
        left: 100%;
    }
    
    .mobile-menu-link:hover {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.3);
        transform: translateX(5px);
    }
    
    .mobile-menu-link i {
        width: 24px;
        text-align: center;
        font-size: 1.2rem;
    }
    
    .mobile-dropdown-toggle {
        display: flex;
        align-items: center;
        justify-content: space-between;
        width: 100%;
        padding: 16px 18px;
        background: rgba(6, 182, 212, 0.08);
        border: 1px solid rgba(6, 182, 212, 0.2);
        color: rgba(255, 255, 255, 0.95);
        font-weight: 600;
        font-size: 1rem;
        text-align: left;
        border-radius: 12px;
        cursor: pointer;
        transition: all 0.3s ease;
        margin-bottom: 8px;
    }
    
    .mobile-dropdown-toggle:hover {
        background: rgba(6, 182, 212, 0.15);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.4);
        transform: translateX(3px);
    }
    
    .mobile-dropdown-toggle i:first-child {
        margin-right: 12px;
        width: 24px;
        text-align: center;
        font-size: 1.2rem;
    }
    
    .mobile-dropdown-toggle .dropdown-icon {
        transition: transform 0.3s ease;
        font-size: 0.9rem;
    }
    
    .mobile-dropdown-toggle.active .dropdown-icon {
        transform: rotate(180deg);
    }
    
    .mobile-dropdown-content {
        max-height: 0;
        overflow: hidden;
        transition: max-height 0.4s cubic-bezier(0.4, 0, 0.2, 1);
        padding-left: 25px;
        margin-top: 5px;
    }
    
    .mobile-dropdown-content.active {
        max-height: 1000px;
    }
    
    .mobile-dropdown-item {
        display: flex;
        align-items: center;
        gap: 12px;
        padding: 14px 18px;
        color: rgba(255, 255, 255, 0.85);
        text-decoration: none;
        border-radius: 10px;
        margin-top: 6px;
        margin-bottom: 4px;
        transition: all 0.3s ease;
        font-weight: 500;
        border: 1px solid transparent;
    }
    
    .mobile-dropdown-item i {
        width: 20px;
        text-align: center;
        font-size: 1.1rem;
    }
    
    .mobile-dropdown-item:hover {
        background: rgba(6, 182, 212, 0.12);
        color: #06b6d4;
        border-color: rgba(6, 182, 212, 0.25);
        transform: translateX(8px);
        padding-left: 22px;
    }
    
    /* Language dans le menu mobile */
    .mobile-language-flag {
        border-radius: 2px;
        object-fit: cover;
        display: inline-block;
        vertical-align: middle;
    }
    
    .mobile-menu-link[onclick="toggleLanguage()"] {
        display: flex;
        align-items: center;
        justify-content: flex-start;
        cursor: pointer;
    }
    
    .mobile-menu-link[onclick="toggleLanguage()"]:hover {
        background: rgba(6, 182, 212, 0.2) !important;
        border-color: rgba(6, 182, 212, 0.5) !important;
        color: #06b6d4 !important;
    }
    
    /* Responsive */
    @media (max-width: 968px) {
        .navbar-menu {
            display: none;
        }
        
        .mobile-toggle {
            display: flex;
        }
        
        .navbar-cta {
            display: none;
        }
        
        .navbar-search-container {
            margin-right: 10px;
        }
        
        .navbar-language-widget {
            display: none !important;
        }
        
        .navbar-search-icon {
            width: 36px;
            height: 36px;
            font-size: 0.9rem;
            margin-right: 8px;
        }
        
        .navbar-search-form {
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            margin: 0;
            width: 100%;
            max-width: 100%;
            border-radius: 0;
            padding: 20px;
            z-index: 10001;
            display: flex;
            flex-direction: column;
            justify-content: flex-start;
            background: linear-gradient(135deg, rgba(15, 23, 42, 0.98) 0%, rgba(30, 41, 59, 0.98) 100%);
            backdrop-filter: blur(40px);
            -webkit-backdrop-filter: blur(40px);
        }
        
        .navbar-search-form::after {
            content: '';
            position: fixed;
            top: 0;
            left: 0;
            right: 0;
            bottom: 0;
            background: rgba(0, 0, 0, 0.5);
            z-index: -1;
            opacity: 0;
            transition: opacity 0.3s ease;
        }
        
        .navbar-search-form.active::after {
            opacity: 1;
        }
        
        .navbar-search-form.active {
            transform: translateY(0) scale(1);
        }
        
        .search-header {
            margin-bottom: 25px;
            padding-bottom: 20px;
        }
        
        .search-wrapper {
            flex-direction: column;
            gap: 15px;
        }
        
        .search-input {
            width: 100%;
            padding: 18px 20px;
            font-size: 1.1rem;
        }
        
        .search-button {
            width: 100%;
            padding: 18px 28px;
            font-size: 1rem;
        }
        
        .search-hint {
            margin-top: 20px;
            padding: 15px;
            font-size: 0.9rem;
        }
    }
    
    @media (max-width: 480px) {
        .navbar-container {
            padding: 0 15px;
            height: 65px;
        }
        
        .logo-text {
            font-size: 0.95rem;
        }
        
        .logo-image {
            width: 35px;
            height: 35px;
        }
        
        .navbar-search-form {
            padding: 15px;
        }
    }
</style>

<nav class="navbar-modern" id="navigation" role="navigation" aria-label="Navigation principale">
    <div class="navbar-container">
        <!-- Logo -->
        <a href="{{ route('home') }}" class="navbar-logo">
            <img src="{{ asset('images/logo.png') }}" alt="Logo" class="logo-image">
            <span class="logo-text">NiangProgrammeur</span>
        </a>
        
        <!-- Desktop Menu -->
        <ul class="navbar-menu">
            <li class="navbar-item">
                <a href="{{ route('home') }}" class="navbar-link {{ request()->routeIs('home') ? 'active' : '' }}">
                    {{ trans('app.nav.home') }}
                </a>
            </li>
            
            <!-- Dropdown Formations -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    {{ trans('app.nav.formations') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('formations.all') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-graduation-cap" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.all') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.all_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.html5') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(227, 76, 38, 0.1);">
                            <i class="fab fa-html5" style="color: #e34c26;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.html5') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.html5_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.css3') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(38, 77, 228, 0.1);">
                            <i class="fab fa-css3-alt" style="color: #264de4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.css3') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.css3_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.javascript') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(240, 219, 79, 0.1);">
                            <i class="fab fa-js" style="color: #f0db4f;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.javascript') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.javascript_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.php') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(137, 147, 190, 0.1);">
                            <i class="fab fa-php" style="color: #8993be;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.php') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.php_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.bootstrap') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(121, 82, 179, 0.1);">
                            <i class="fab fa-bootstrap" style="color: #7952b3;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.bootstrap') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.bootstrap_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.git') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(243, 79, 41, 0.1);">
                            <i class="fab fa-git-alt" style="color: #f34f29;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.git') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.git_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('formations.java') }}" class="dropdown-item" data-parent-active="formations">
                        <div class="dropdown-item-icon" style="background: rgba(237, 139, 0, 0.1);">
                            <i class="fab fa-java" style="color: #ed8b00;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.formations.java') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.formations.java_desc') }}</div>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- Dropdown Pratique -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    {{ trans('app.nav.practice') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('exercices') }}" class="dropdown-item" data-parent-active="pratique">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-laptop-code" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.practice.exercices') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.practice.exercices_desc') }}</div>
                        </div>
                    </a>
                    <a href="{{ route('quiz') }}" class="dropdown-item" data-parent-active="pratique">
                        <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                            <i class="fas fa-question-circle" style="color: #a855f7;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.practice.quiz') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.practice.quiz_desc') }}</div>
                        </div>
                    </a>
                </div>
            </li>
            
            <!-- Dropdown Emplois -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle">
                    {{ trans('app.nav.jobs') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('emplois') }}" class="dropdown-item" data-parent-active="emplois">
                        <div class="dropdown-item-icon" style="background: rgba(34, 197, 94, 0.1);">
                            <i class="fas fa-search" style="color: #22c55e;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.all') }}</div>
                            <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.all_desc') }}</div>
                        </div>
                    </a>
                    @if(isset($jobCategories) && $jobCategories->count() > 0)
                        @foreach($jobCategories as $category)
                        <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                                @if($category->icon)
                                    <i class="{{ $category->icon }}" style="color: #06b6d4;"></i>
                                @else
                                    <i class="fas fa-folder" style="color: #06b6d4;"></i>
                                @endif
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ $category->name }}</div>
                                <div class="dropdown-item-desc">{{ $category->published_articles_count ?? 0 }} {{ trans('app.nav.dropdown.jobs.articles') }}</div>
                            </div>
                        </a>
                        @endforeach
                    @else
                        <a href="{{ route('emplois.offres') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(59, 130, 246, 0.1);">
                                <i class="fas fa-briefcase" style="color: #3b82f6;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.offers') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.offers_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.bourses') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(168, 85, 247, 0.1);">
                                <i class="fas fa-graduation-cap" style="color: #a855f7;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.scholarships') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.scholarships_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.candidature') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(236, 72, 153, 0.1);">
                                <i class="fas fa-paper-plane" style="color: #ec4899;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.application') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.application_desc') }}</div>
                            </div>
                        </a>
                        <a href="{{ route('emplois.opportunites') }}" class="dropdown-item" data-parent-active="emplois">
                            <div class="dropdown-item-icon" style="background: rgba(251, 191, 36, 0.1);">
                                <i class="fas fa-star" style="color: #fbbf24;"></i>
                            </div>
                            <div class="dropdown-item-content">
                                <div class="dropdown-item-title">{{ trans('app.nav.dropdown.jobs.opportunities') }}</div>
                                <div class="dropdown-item-desc">{{ trans('app.nav.dropdown.jobs.opportunities_desc') }}</div>
                            </div>
                        </a>
                    @endif
                </div>
            </li>
            
            <!-- Dropdown À propos / Contact -->
            <li class="navbar-item dropdown">
                <a href="#" class="navbar-link dropdown-toggle {{ request()->routeIs(['about', 'contact']) ? 'active' : '' }}">
                    {{ trans('app.nav.about') }}
                    <i class="fas fa-chevron-down dropdown-icon"></i>
                </a>
                <div class="dropdown-menu">
                    <a href="{{ route('about') }}" class="dropdown-item" data-parent-active="about">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-info-circle" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.about') }}</div>
                            <div class="dropdown-item-desc">En savoir plus sur NiangProgrammeur</div>
                        </div>
                    </a>
                    <a href="{{ route('contact') }}" class="dropdown-item" data-parent-active="contact">
                        <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.1);">
                            <i class="fas fa-envelope" style="color: #06b6d4;"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title">{{ trans('app.nav.contact') }}</div>
                            <div class="dropdown-item-desc">Contactez-nous</div>
                        </div>
                    </a>
                </div>
            </li>
        </ul>
        
        <!-- Language Toggle (dans la navbar) -->
        @php
            $showLanguageWidget = request()->routeIs([
                'home',
                'about',
                'contact',
                'login',
                'register',
                'formations.all',
                'formations.html5',
                'formations.css3',
                'formations.javascript',
                'formations.php',
                'formations.bootstrap',
                'formations.java',
                'formations.sql',
                'formations.c',
                'formations.cpp',
                'formations.csharp',
                'formations.dart',
                'formations.git',
                'formations.wordpress',
                'formations.ia',
                'formations.python',
                'exercices',
                'exercices.language',
                'exercices.detail',
                'exercices.run',
                'quiz',
                'quiz.language',
                'quiz.result',
                'dashboard.*'
            ]);
        @endphp
        
        @if($showLanguageWidget)
        @php
            $currentLang = app()->getLocale();
            $tooltipText = $currentLang === 'fr' ? 'Changer en anglais' : 'Switch to French';
        @endphp
        <div class="navbar-language-widget">
            <button id="language-toggle" class="navbar-language-button" onclick="toggleLanguage()" title="{{ $tooltipText }}" aria-label="{{ $tooltipText }}">
                @if($currentLang === 'fr')
                <!-- Drapeau français -->
                <svg class="navbar-language-flag" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg">
                    <g fill-rule="evenodd" stroke-width="1pt">
                        <path fill="#fff" d="M0 0h640v480H0z"/>
                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                    </g>
                </svg>
                @else
                <!-- Drapeau américain simplifié -->
                <svg class="navbar-language-flag" viewBox="0 0 7410 3900" xmlns="http://www.w3.org/2000/svg">
                    <path fill="#b22234" d="M0 0h7410v3900H0z"/>
                    <path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/>
                    <path fill="#3c3b6e" d="M0 0h2964v2100H0z"/>
                    <g fill="#fff">
                        <g id="star">
                            <path d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/>
                        </g>
                        <use href="#star" x="988" y="210"/>
                        <use href="#star" x="1976" y="420"/>
                        <use href="#star" x="494" y="420"/>
                        <use href="#star" x="1482" y="630"/>
                        <use href="#star" x="2470" y="630"/>
                        <use href="#star" x="988" y="840"/>
                        <use href="#star" x="1976" y="840"/>
                        <use href="#star" x="494" y="1050"/>
                        <use href="#star" x="1482" y="1260"/>
                        <use href="#star" x="2470" y="1260"/>
                        <use href="#star" x="988" y="1470"/>
                        <use href="#star" x="1976" y="1470"/>
                        <use href="#star" x="494" y="1680"/>
                        <use href="#star" x="1482" y="1890"/>
                        <use href="#star" x="2470" y="1890"/>
                    </g>
                </svg>
                @endif
                <span class="navbar-language-tooltip" id="language-tooltip">{{ $tooltipText }}</span>
            </button>
        </div>
        @endif
        
        <!-- Search Icon -->
        <div class="navbar-search-container" style="position: relative;">
            <button type="button" class="navbar-search-icon" id="searchIcon" aria-label="{{ trans('app.home.search.button') }}" aria-expanded="false" aria-controls="searchForm">
                <i class="fas fa-search" aria-hidden="true"></i>
                <span class="sr-only">{{ trans('app.home.search.button') }}</span>
            </button>
            
            <!-- Search Form (hidden by default) -->
            <form action="{{ route('search') }}" method="GET" class="navbar-search-form" id="searchForm" role="search">
                <div class="search-header">
                    <div class="search-title">
                        <i class="fas fa-search"></i>
                        <span>{{ trans('app.home.search.title') }}</span>
                    </div>
                    <button type="button" class="search-close" id="searchClose" aria-label="{{ trans('app.home.search.close') }}">
                        <i class="fas fa-times"></i>
                    </button>
                </div>
                <div class="search-wrapper">
                    <input type="search" name="q" id="navbarSearch" 
                           value="{{ request('q') }}" 
                           placeholder="{{ trans('app.home.search.placeholder') }}" 
                           class="search-input"
                           aria-label="{{ trans('app.home.search.label') }}"
                           autocomplete="off">
                    <button type="submit" class="search-button" aria-label="{{ trans('app.home.search.submit') }}">
                        <i class="fas fa-search"></i>
                        <span>{{ trans('app.home.search.button') }}</span>
                    </button>
                </div>
                <div class="search-hint">
                    <i class="fas fa-lightbulb"></i>
                    <span>{{ trans('app.home.search.hint') }}</span>
                </div>
            </form>
        </div>
        
        <!-- Notification Widget (dans la navbar) -->
        @auth
        <div class="navbar-item" id="notification-widget-container" style="position: relative;">
            <button type="button" class="navbar-notification-bell" id="notificationBell" aria-label="Notifications" onclick="toggleNotificationDropdown(event);" style="
                background: none;
                border: none;
                color: rgba(255, 255, 255, 0.9);
                font-size: 1.2rem;
                cursor: pointer;
                padding: 8px 12px;
                border-radius: 8px;
                transition: all 0.3s ease;
                position: relative;
                display: flex;
                align-items: center;
                justify-content: center;
            ">
                <i class="fas fa-bell"></i>
                <span class="navbar-notification-badge" id="notificationBadge" style="
                    position: absolute;
                    top: 4px;
                    right: 4px;
                    background: #ef4444;
                    color: white;
                    border-radius: 50%;
                    width: 18px;
                    height: 18px;
                    display: none;
                    align-items: center;
                    justify-content: center;
                    font-size: 0.65rem;
                    font-weight: 700;
                    border: 2px solid rgba(15, 23, 42, 0.95);
                    animation: pulse 2s infinite;
                ">0</span>
            </button>
            <div class="notification-dropdown navbar-notification-dropdown" id="notificationDropdown" style="
                position: absolute;
                top: calc(100% + 15px);
                right: 0;
                width: 380px;
                max-height: 500px;
                background: white;
                border-radius: 16px;
                box-shadow: 0 10px 40px rgba(0, 0, 0, 0.2);
                display: none;
                flex-direction: column;
                overflow: hidden;
                transform: translateY(20px);
                opacity: 0;
                transition: all 0.3s cubic-bezier(0.68, -0.55, 0.265, 1.55);
                z-index: 10000;
            ">
                <div class="notification-header">
                    <h4>Notifications</h4>
                    <button type="button" class="mark-all-read" id="markAllRead" onclick="markAllNotificationsAsRead(event);">Tout marquer comme lu</button>
                </div>
                <div class="notification-list" id="notificationList">
                    <div class="notification-loading">Chargement...</div>
                </div>
                <div class="notification-footer">
                    <a href="/dashboard/notifications">Voir toutes les notifications</a>
                </div>
            </div>
        </div>
        @endauth
        
        <!-- User Dropdown -->
        @auth
        <div class="navbar-item dropdown" id="userDropdown">
            <a href="#" class="navbar-cta dropdown-toggle" aria-label="Menu utilisateur" onclick="event.preventDefault(); toggleUserDropdown();" style="padding: 6px 10px; min-width: auto; height: auto;">
                <div style="display: flex; align-items: center; gap: 6px;">
                    <div style="width: 28px; height: 28px; border-radius: 50%; background: linear-gradient(135deg, #06b6d4, #14b8a6); display: flex; align-items: center; justify-content: center; color: white; font-weight: 700; font-size: 0.75rem; flex-shrink: 0;">
                        {{ strtoupper(substr(Auth::user()->name, 0, 1)) }}
                    </div>
                    <span style="max-width: 100px; overflow: hidden; text-overflow: ellipsis; white-space: nowrap; font-size: 0.75rem;">{{ Auth::user()->name }}</span>
                    <i class="fas fa-chevron-down dropdown-icon" style="font-size: 0.6rem; margin-left: 2px;"></i>
                </div>
            </a>
            <div class="dropdown-menu" id="userDropdownMenu" style="right: 0; left: auto; min-width: 220px;">
                <a href="{{ route('dashboard.overview') }}" class="dropdown-item">
                    <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
                        <i class="fas fa-tachometer-alt"></i>
                    </div>
                    <div class="dropdown-item-content">
                        <div class="dropdown-item-title">Dashboard</div>
                    </div>
                </a>
                <a href="{{ route('dashboard.profile') }}" class="dropdown-item">
                    <div class="dropdown-item-icon" style="background: rgba(6, 182, 212, 0.2); color: #06b6d4;">
                        <i class="fas fa-user"></i>
                    </div>
                    <div class="dropdown-item-content">
                        <div class="dropdown-item-title">Profil</div>
                    </div>
                </a>
                <div class="dropdown-divider"></div>
                <form method="POST" action="{{ route('logout') }}">
                    @csrf
                    <button type="submit" class="dropdown-item danger" style="width: 100%; border: none; background: none; text-align: left; cursor: pointer; padding: 12px 16px;">
                        <div class="dropdown-item-icon" style="background: rgba(239, 68, 68, 0.2); color: #ef4444;">
                            <i class="fas fa-sign-out-alt"></i>
                        </div>
                        <div class="dropdown-item-content">
                            <div class="dropdown-item-title" style="color: #ef4444;">Déconnexion</div>
                        </div>
                    </button>
                </form>
            </div>
        </div>
        @else
        <a href="{{ route('login') }}" class="navbar-cta" aria-label="Connexion">
            <i class="fas fa-sign-in-alt" aria-hidden="true"></i>
            {{ trans('app.nav.login') ?? 'Connexion' }}
        </a>
        @endauth
        
        <!-- Mobile Toggle -->
        <button class="mobile-toggle" id="mobileToggle" aria-label="Ouvrir le menu mobile" aria-expanded="false">
            <span class="mobile-toggle-line" aria-hidden="true"></span>
            <span class="mobile-toggle-line" aria-hidden="true"></span>
            <span class="mobile-toggle-line" aria-hidden="true"></span>
        </button>
    </div>
</nav>

<!-- Mobile Menu Overlay -->
<div class="mobile-menu-overlay" id="mobileMenuOverlay"></div>

<!-- Mobile Menu -->
<div class="mobile-menu" id="mobileMenu">
    <div class="mobile-menu-header">
        <div class="mobile-menu-title">Menu</div>
        <button class="mobile-menu-close" onclick="closeMobileMenu()">
            <i class="fas fa-times"></i>
        </button>
    </div>
    <ul class="mobile-menu-list">
        <li class="mobile-menu-item">
            <a href="{{ route('home') }}" class="mobile-menu-link">
                <i class="fas fa-home"></i>
                {{ trans('app.nav.home') }}
            </a>
        </li>
        
        <!-- Mobile Dropdown Formations -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('formations')">
                <span><i class="fas fa-graduation-cap"></i> {{ trans('app.nav.formations') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="formations-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="formations-dropdown">
                <a href="{{ route('formations.all') }}" class="mobile-dropdown-item">
                    <i class="fas fa-graduation-cap" style="color: #06b6d4;"></i> {{ trans('app.nav.dropdown.formations.all') }}
                </a>
                <a href="{{ route('formations.html5') }}" class="mobile-dropdown-item">
                    <i class="fab fa-html5" style="color: #e34c26;"></i> HTML5
                </a>
                <a href="{{ route('formations.css3') }}" class="mobile-dropdown-item">
                    <i class="fab fa-css3-alt" style="color: #264de4;"></i> CSS3
                </a>
                <a href="{{ route('formations.javascript') }}" class="mobile-dropdown-item">
                    <i class="fab fa-js" style="color: #f0db4f;"></i> JavaScript
                </a>
                <a href="{{ route('formations.php') }}" class="mobile-dropdown-item">
                    <i class="fab fa-php" style="color: #8993be;"></i> PHP
                </a>
                <a href="{{ route('formations.bootstrap') }}" class="mobile-dropdown-item">
                    <i class="fab fa-bootstrap" style="color: #7952b3;"></i> Bootstrap
                </a>
                <a href="{{ route('formations.git') }}" class="mobile-dropdown-item">
                    <i class="fab fa-git-alt" style="color: #f34f29;"></i> Git
                </a>
                <a href="{{ route('formations.java') }}" class="mobile-dropdown-item">
                    <i class="fab fa-java" style="color: #ed8b00;"></i> Java
                </a>
            </div>
        </li>
        
        <!-- Mobile Dropdown Pratique -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('pratique')">
                <span><i class="fas fa-code"></i> {{ trans('app.nav.practice') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="pratique-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="pratique-dropdown">
                <a href="{{ route('exercices') }}" class="mobile-dropdown-item">
                    <i class="fas fa-laptop-code" style="color: #06b6d4;"></i> {{ trans('app.nav.dropdown.practice.exercices') }}
                </a>
                <a href="{{ route('quiz') }}" class="mobile-dropdown-item">
                    <i class="fas fa-question-circle" style="color: #a855f7;"></i> {{ trans('app.nav.dropdown.practice.quiz') }}
                </a>
            </div>
        </li>
        
        <!-- Mobile Dropdown Emplois -->
        <li class="mobile-menu-item">
            <button class="mobile-dropdown-toggle" onclick="toggleMobileDropdown('emplois')">
                <span><i class="fas fa-briefcase"></i> {{ trans('app.nav.jobs') }}</span>
                <i class="fas fa-chevron-down dropdown-icon" id="emplois-icon"></i>
            </button>
            <div class="mobile-dropdown-content" id="emplois-dropdown">
                <a href="{{ route('emplois') }}" class="mobile-dropdown-item">
                    <i class="fas fa-search" style="color: #22c55e;"></i> {{ trans('app.nav.dropdown.jobs.all') }}
                </a>
                @if(isset($jobCategories) && $jobCategories->count() > 0)
                    @foreach($jobCategories as $category)
                    <a href="{{ route('emplois.offres') }}?category={{ $category->slug }}" class="mobile-dropdown-item">
                        @if($category->icon)
                            <i class="{{ $category->icon }}" style="color: #06b6d4;"></i>
                        @else
                            <i class="fas fa-folder" style="color: #06b6d4;"></i>
                        @endif
                        {{ $category->name }}
                    </a>
                    @endforeach
                @else
                    <a href="{{ route('emplois.offres') }}" class="mobile-dropdown-item">
                        <i class="fas fa-briefcase" style="color: #3b82f6;"></i> {{ trans('app.nav.dropdown.jobs.offers') }}
                    </a>
                    <a href="{{ route('emplois.bourses') }}" class="mobile-dropdown-item">
                        <i class="fas fa-graduation-cap" style="color: #a855f7;"></i> {{ trans('app.nav.dropdown.jobs.scholarships') }}
                    </a>
                    <a href="{{ route('emplois.candidature') }}" class="mobile-dropdown-item">
                        <i class="fas fa-paper-plane" style="color: #ec4899;"></i> {{ trans('app.nav.dropdown.jobs.application') }}
                    </a>
                    <a href="{{ route('emplois.opportunites') }}" class="mobile-dropdown-item">
                        <i class="fas fa-star" style="color: #fbbf24;"></i> {{ trans('app.nav.dropdown.jobs.opportunities') }}
                    </a>
                @endif
            </div>
        </li>
        
        <li class="mobile-menu-item">
            <a href="{{ route('about') }}" class="mobile-menu-link">
                <i class="fas fa-info-circle"></i>
                {{ trans('app.nav.about') }}
            </a>
        </li>
        
        <li class="mobile-menu-item">
            <a href="{{ route('contact') }}" class="mobile-menu-link">
                <i class="fas fa-envelope"></i>
                {{ trans('app.nav.contact') }}
            </a>
        </li>
        
        <!-- Language Toggle dans le menu mobile -->
        @php
            $showLanguageWidget = request()->routeIs([
                'home',
                'about',
                'contact',
                'formations.all',
                'formations.html5',
                'formations.css3',
                'formations.javascript',
                'formations.php',
                'formations.bootstrap',
                'formations.java',
                'formations.sql',
                'formations.c',
                'formations.cpp',
                'formations.csharp',
                'formations.dart',
                'formations.git',
                'formations.wordpress',
                'formations.ia',
                'formations.python',
                'exercices',
                'exercices.language',
                'exercices.detail',
                'exercices.run',
                'quiz',
                'quiz.language',
                'quiz.result',
                'dashboard.*'
            ]);
        @endphp
        
        @if($showLanguageWidget)
        @php
            $currentLang = app()->getLocale();
            $tooltipText = $currentLang === 'fr' ? 'Changer en anglais' : 'Switch to French';
        @endphp
        <li class="mobile-menu-item">
            <a href="javascript:void(0)" class="mobile-menu-link" onclick="toggleLanguage()" style="background: rgba(6, 182, 212, 0.1); border: 1px solid rgba(6, 182, 212, 0.3);">
                @if($currentLang === 'fr')
                <!-- Drapeau français -->
                <svg class="mobile-language-flag" viewBox="0 0 640 480" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 18px; margin-right: 12px;">
                    <g fill-rule="evenodd" stroke-width="1pt">
                        <path fill="#fff" d="M0 0h640v480H0z"/>
                        <path fill="#00267f" d="M0 0h213.3v480H0z"/>
                        <path fill="#f31830" d="M426.7 0H640v480H426.7z"/>
                    </g>
                </svg>
                @else
                <!-- Drapeau américain simplifié -->
                <svg class="mobile-language-flag" viewBox="0 0 7410 3900" xmlns="http://www.w3.org/2000/svg" style="width: 24px; height: 18px; margin-right: 12px;">
                    <path fill="#b22234" d="M0 0h7410v3900H0z"/>
                    <path d="M0 450h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0m0 600h7410m0 600H0" stroke="#fff" stroke-width="300"/>
                    <path fill="#3c3b6e" d="M0 0h2964v2100H0z"/>
                    <g fill="#fff">
                        <g id="star">
                            <path d="M247 90l70.534 217.082-184.66-134.164h228.253L176.466 307.082z"/>
                        </g>
                        <use href="#star" x="988" y="210"/>
                        <use href="#star" x="1976" y="420"/>
                        <use href="#star" x="494" y="420"/>
                        <use href="#star" x="1482" y="630"/>
                        <use href="#star" x="2470" y="630"/>
                        <use href="#star" x="988" y="840"/>
                        <use href="#star" x="1976" y="840"/>
                        <use href="#star" x="494" y="1050"/>
                        <use href="#star" x="1482" y="1260"/>
                        <use href="#star" x="2470" y="1260"/>
                        <use href="#star" x="988" y="1470"/>
                        <use href="#star" x="1976" y="1470"/>
                        <use href="#star" x="494" y="1680"/>
                        <use href="#star" x="1482" y="1890"/>
                        <use href="#star" x="2470" y="1890"/>
                    </g>
                </svg>
                @endif
                <span>{{ $tooltipText }}</span>
            </a>
        </li>
        @endif
        
        @auth
        <li class="mobile-menu-item">
            <a href="{{ route('dashboard.overview') }}" class="mobile-menu-link" style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; font-weight: 700;">
                <i class="fas fa-tachometer-alt"></i>
                Dashboard
            </a>
        </li>
        <li class="mobile-menu-item">
            <a href="{{ route('dashboard.profile') }}" class="mobile-menu-link">
                <i class="fas fa-user"></i>
                Profil
            </a>
        </li>
        <li class="mobile-menu-item">
            <form method="POST" action="{{ route('logout') }}" style="margin: 0;">
                @csrf
                <button type="submit" class="mobile-menu-link" style="width: 100%; border: none; background: rgba(239, 68, 68, 0.1); color: #ef4444; text-align: left; cursor: pointer;">
                    <i class="fas fa-sign-out-alt"></i>
                    Déconnexion
                </button>
            </form>
        </li>
        @else
        <li class="mobile-menu-item">
            <a href="{{ route('login') }}" class="mobile-menu-link" style="background: linear-gradient(135deg, #06b6d4, #14b8a6); color: #000; font-weight: 700;">
                <i class="fas fa-sign-in-alt"></i>
                {{ trans('app.nav.login') ?? 'Connexion' }}
            </a>
        </li>
        @endauth
    </ul>
</div>

<script>
    // Navbar scroll effect
    window.addEventListener('scroll', () => {
        const navbar = document.getElementById('navbar');
        if (window.scrollY > 50) {
            navbar.classList.add('scrolled');
        } else {
            navbar.classList.remove('scrolled');
        }
    });
    
    // Mobile menu toggle
    const mobileToggle = document.getElementById('mobileToggle');
    const mobileMenu = document.getElementById('mobileMenu');
    const mobileMenuOverlay = document.getElementById('mobileMenuOverlay');
    
    function openMobileMenu() {
        mobileToggle.classList.add('active');
        mobileMenu.classList.add('active');
        mobileMenuOverlay.classList.add('active');
        document.body.style.overflow = 'hidden';
    }
    
    function closeMobileMenu() {
        mobileToggle.classList.remove('active');
        mobileMenu.classList.remove('active');
        mobileMenuOverlay.classList.remove('active');
        document.body.style.overflow = '';
    }
    
    mobileToggle.addEventListener('click', () => {
        if (mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        } else {
            openMobileMenu();
        }
    });
    
    mobileMenuOverlay.addEventListener('click', closeMobileMenu);
    
    // Mobile dropdown toggle
    function toggleMobileDropdown(id) {
        const dropdown = document.getElementById(id + '-dropdown');
        const icon = document.getElementById(id + '-icon');
        const toggle = event.target.closest('.mobile-dropdown-toggle');
        
        dropdown.classList.toggle('active');
        if (toggle) {
            toggle.classList.toggle('active');
        }
        if (icon) {
        icon.style.transform = dropdown.classList.contains('active') ? 'rotate(180deg)' : 'rotate(0deg)';
        }
    }
    
    // Close mobile menu when clicking outside
    document.addEventListener('click', (e) => {
        if (!e.target.closest('.navbar-modern') && !e.target.closest('.mobile-menu') && !e.target.closest('.mobile-menu-overlay')) {
            closeMobileMenu();
        }
    });
    
    // User Dropdown Toggle
    function toggleUserDropdown() {
        const dropdown = document.getElementById('userDropdown');
        const menu = document.getElementById('userDropdownMenu');
        if (dropdown && menu) {
            const isActive = dropdown.classList.contains('active');
            if (isActive) {
                dropdown.classList.remove('active');
                menu.style.opacity = '0';
                menu.style.visibility = 'hidden';
                menu.style.transform = 'translateY(-15px) scale(0.95)';
            } else {
                dropdown.classList.add('active');
                menu.style.opacity = '1';
                menu.style.visibility = 'visible';
                menu.style.transform = 'translateY(0) scale(1)';
            }
        }
    }
    
    // Close user dropdown when clicking outside
    document.addEventListener('click', (e) => {
        const userDropdown = document.getElementById('userDropdown');
        const userDropdownMenu = document.getElementById('userDropdownMenu');
        if (userDropdown && userDropdownMenu && !userDropdown.contains(e.target)) {
            userDropdown.classList.remove('active');
            userDropdownMenu.style.opacity = '0';
            userDropdownMenu.style.visibility = 'hidden';
            userDropdownMenu.style.transform = 'translateY(-15px) scale(0.95)';
        }
    });
    
    // Close on escape key
    document.addEventListener('keydown', (e) => {
        if (e.key === 'Escape' && mobileMenu.classList.contains('active')) {
            closeMobileMenu();
        }
        if (e.key === 'Escape') {
            const searchForm = document.getElementById('searchForm');
            if (searchForm && searchForm.classList.contains('active')) {
                closeSearchForm();
            }
            const userDropdown = document.getElementById('userDropdown');
            const userDropdownMenu = document.getElementById('userDropdownMenu');
            if (userDropdown && userDropdownMenu && userDropdown.classList.contains('active')) {
                userDropdown.classList.remove('active');
                userDropdownMenu.style.opacity = '0';
                userDropdownMenu.style.visibility = 'hidden';
                userDropdownMenu.style.transform = 'translateY(-15px) scale(0.95)';
            }
        }
    });
    
    // Search Form Toggle
    const searchIcon = document.getElementById('searchIcon');
    const searchForm = document.getElementById('searchForm');
    const searchClose = document.getElementById('searchClose');
    const navbarSearch = document.getElementById('navbarSearch');
    
    function openSearchForm() {
        if (searchForm) {
            searchForm.classList.add('active');
            document.body.style.overflow = 'hidden';
            setTimeout(() => {
                if (navbarSearch) {
                    navbarSearch.focus();
                }
            }, 100);
        }
    }
    
    function closeSearchForm() {
        if (searchForm) {
            searchForm.classList.remove('active');
            document.body.style.overflow = '';
            if (navbarSearch) {
                navbarSearch.blur();
            }
        }
    }
    
    if (searchIcon) {
        searchIcon.addEventListener('click', function(e) {
            e.stopPropagation();
            if (searchForm && searchForm.classList.contains('active')) {
                closeSearchForm();
            } else {
                openSearchForm();
            }
        });
    }
    
    if (searchClose) {
        searchClose.addEventListener('click', function(e) {
            e.stopPropagation();
            closeSearchForm();
        });
    }
    
    // Fermer le formulaire de recherche si on clique en dehors
    document.addEventListener('click', function(e) {
        if (searchForm && searchIcon) {
            if (!searchForm.contains(e.target) && !searchIcon.contains(e.target)) {
                closeSearchForm();
            }
        }
    });
    
    // Empêcher la fermeture quand on clique dans le formulaire
    if (searchForm) {
        searchForm.addEventListener('click', function(e) {
            e.stopPropagation();
        });
    }
    
    // Ajouter la classe active sur le parent du dropdown quand on clique sur un élément
    document.querySelectorAll('.dropdown-item[data-parent-active]').forEach(item => {
        item.addEventListener('click', function() {
            const parentId = this.getAttribute('data-parent-active');
            const parentDropdown = this.closest('.dropdown');
            
            if (parentDropdown) {
                // Retirer active de tous les autres dropdowns
                document.querySelectorAll('.dropdown').forEach(dropdown => {
                    dropdown.querySelector('.navbar-link')?.classList.remove('active');
                });
                
                // Ajouter active au parent
                const parentLink = parentDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        });
    });
    
    // Vérifier l'URL actuelle et activer le parent si nécessaire
    document.addEventListener('DOMContentLoaded', function() {
        const currentPath = window.location.pathname;
        
        // Vérifier les routes de formations
        if (currentPath.includes('/formations/')) {
            const formationsDropdown = document.querySelector('.dropdown:has([href*="/formations/"])');
            if (formationsDropdown) {
                const parentLink = formationsDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
        
        // Vérifier les routes de pratique (exercices/quiz)
        if (currentPath.includes('/exercices/') || currentPath.includes('/quiz/')) {
            const pratiqueDropdown = document.querySelector('.dropdown:has([href*="/exercices"], [href*="/quiz"])');
            if (pratiqueDropdown) {
                const parentLink = pratiqueDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
        
        // Vérifier les routes d'emplois
        if (currentPath.includes('/emplois/')) {
            const emploisDropdown = document.querySelector('.dropdown:has([href*="/emplois"])');
            if (emploisDropdown) {
                const parentLink = emploisDropdown.querySelector('.navbar-link');
                if (parentLink) {
                    parentLink.classList.add('active');
                }
            }
        }
    });
    
    // Fonction globale pour toggle le dropdown de notifications
    window.toggleNotificationDropdown = function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        const dropdown = document.getElementById('notificationDropdown');
        if (!dropdown) {
            console.error('Dropdown de notifications non trouvé');
            return;
        }
        
        const isActive = dropdown.classList.contains('active');
        if (isActive) {
            dropdown.classList.remove('active');
        } else {
            dropdown.classList.add('active');
            
            // Charger les notifications si nécessaire
            const list = document.getElementById('notificationList');
            if (list && (list.innerHTML.includes('Chargement') || list.innerHTML.includes('Aucune notification'))) {
                // Utiliser le manager s'il existe, sinon charger directement
                if (window.notificationManager && typeof window.notificationManager.loadNotifications === 'function') {
                    window.notificationManager.loadNotifications();
                } else {
                    // Charger les notifications directement
                    loadNotificationsDirectly();
                }
            }
        }
    };
    
    // Fonction pour charger les notifications directement
    window.loadNotificationsDirectly = async function() {
        if (!window.isAuthenticated) return;
        
        const list = document.getElementById('notificationList');
        const badge = document.getElementById('notificationBadge');
        
        if (!list) return;
        
        try {
            const response = await fetch('/api/notifications/unread', {
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json'
                }
            });
            
            if (response.ok) {
                const data = await response.json();
                const notifications = data.notifications || data || [];
                const count = data.count !== undefined ? data.count : notifications.length;
                
                // Mettre à jour le badge
                if (badge) {
                    if (count > 0) {
                        badge.textContent = count > 99 ? '99+' : count;
                        badge.style.display = 'flex';
                    } else {
                        badge.style.display = 'none';
                    }
                }
                
                // Mettre à jour la liste
                if (notifications.length === 0) {
                    list.innerHTML = '<div class="notification-empty">Aucune notification</div>';
                } else {
                    list.innerHTML = notifications.map(notif => {
                        const icon = getNotificationIcon(notif.type);
                        const time = formatNotificationTime(notif.created_at);
                        const message = notif.message || notif.title || 'Nouvelle notification';
                        const link = notif.link || '/dashboard/notifications';
                        return `
                            <div class="notification-item" onclick="window.location.href='${link}'">
                                <div class="notification-icon"><i class="fas ${icon}"></i></div>
                                <div class="notification-content">
                                    <p>${message}</p>
                                    <span>${time}</span>
                                </div>
                            </div>
                        `;
                    }).join('');
                }
            }
        } catch (error) {
            console.error('Erreur chargement notifications:', error);
            list.innerHTML = '<div class="notification-error">Erreur de chargement</div>';
        }
    };
    
    window.getNotificationIcon = function(type) {
        const icons = {
            'comment_reply': 'fa-reply',
            'new_badge': 'fa-award',
            'formation_completed': 'fa-trophy',
            'default': 'fa-bell'
        };
        return icons[type] || icons.default;
    };
    
    window.formatNotificationTime = function(dateString) {
        const date = new Date(dateString);
        const now = new Date();
        const diff = now - date;
        const minutes = Math.floor(diff / 60000);
        const hours = Math.floor(minutes / 60);
        const days = Math.floor(hours / 24);
        
        if (minutes < 1) return 'À l\'instant';
        if (minutes < 60) return `Il y a ${minutes} min`;
        if (hours < 24) return `Il y a ${hours}h`;
        if (days < 7) return `Il y a ${days}j`;
        return date.toLocaleDateString('fr-FR');
    };
    
    // Fonction pour marquer toutes les notifications comme lues
    window.markAllNotificationsAsRead = async function(event) {
        if (event) {
            event.preventDefault();
            event.stopPropagation();
        }
        
        try {
            const response = await fetch('/api/notifications/read-all', {
                method: 'POST',
                headers: {
                    'X-Requested-With': 'XMLHttpRequest',
                    'Accept': 'application/json',
                    'X-CSRF-TOKEN': document.querySelector('meta[name="csrf-token"]')?.content || ''
                }
            });
            
            if (response.ok) {
                // Recharger les notifications
                loadNotificationsDirectly();
                
                // Utiliser le manager s'il existe
                if (window.notificationManager) {
                    window.notificationManager.loadNotifications();
                }
            }
        } catch (error) {
            console.error('Erreur marquer tout comme lu:', error);
        }
    };
    
    // Attacher le listener au bouton de notification au chargement
    document.addEventListener('DOMContentLoaded', function() {
        const notificationBell = document.getElementById('notificationBell');
        if (notificationBell) {
            // Supprimer les anciens listeners
            const newBell = notificationBell.cloneNode(true);
            notificationBell.parentNode.replaceChild(newBell, notificationBell);
            
            // Attacher le nouveau listener
            newBell.addEventListener('click', function(e) {
                e.preventDefault();
                e.stopPropagation();
                if (typeof window.toggleNotificationDropdown === 'function') {
                    window.toggleNotificationDropdown(e);
                } else {
                    console.error('toggleNotificationDropdown n\'est pas définie');
                }
            });
        }
    });
    
</script>
