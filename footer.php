<style>
    .footer {
        background: #00447b;
        display: flex;
        justify-content: space-between;
        align-items: flex-start;
        padding: 30px 50px;
        flex-wrap: wrap;
        margin-top: auto; /* Pushes footer to bottom if body is flex column */
        color: white;
        width: 100%;
        flex-shrink: 0; /* Prevents shrinking */
    }

    .footer-left {
        display: flex;
        flex-direction: column;
        align-items: center;
    }

    .footer-logo {
        width: 150px;
        height: auto; /* Fixes "smashed" aspect ratio */
        margin-bottom: 15px;
    }

    .social-icons {
        display: flex;
        gap: 15px;
    }

    .social-icons a {
        color: white;
        font-size: 20px;
        transition: color 0.2s;
    }

    .social-icons a:hover {
        color: #ddd;
    }

    .footer-right {
        display: flex;
        gap: 50px;
        flex-wrap: wrap;
    }

    .footer-section {
        display: flex;
        flex-direction: column;
        gap: 10px;
        color: white;
    }

    .footer-topic {
        font-size: 18px;
        font-weight: bold;
        color: white !important; /* Forces white text */
        text-transform: uppercase;
        margin-bottom: 5px;
    }

    .footer-section a {
        color: white;
        text-decoration: none;
        padding: 5px 10px;
        border-radius: 5px;
        font-size: 14px;
        transition: background 0.2s;
    }

    .footer-section a:hover {
        background: rgba(255, 255, 255, 0.1);
        text-decoration: underline;
    }

    /* Responsive adjustments */
    @media (max-width: 768px) {
        .footer {
            flex-direction: column;
            align-items: center;
            text-align: center;
            gap: 30px;
        }
        .footer-right {
            justify-content: center;
            gap: 30px;
        }
    }
</style>

<footer class="footer">
    <div class="footer-left">
        <img src="images/RAPPAHANNOCK_v_White-300x300.png" alt="Logo" class="footer-logo">
    </div>

    <div class="footer-right">
        <div class="footer-section">
            <div class="footer-topic">Connect</div>
            <a href="https://www.facebook.com/RappCASA/" target="_blank">Facebook</a>
            <a href="https://www.instagram.com/rappahannock_casa/" target="_blank">Instagram</a>
            <a href="https://rappahannockcasa.com/" target="_blank">Main Website</a>
        </div>
        <div class="footer-section">
            <div class="footer-topic">Contact Us</div>
            <a href="mailto:rappcasa@gmail.com">rappcasa@gmail.com</a>
            <a href="tel:5407106199">540-710-6199</a>
        </div>
    </div>
</footer>