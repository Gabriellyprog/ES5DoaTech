<?php
if (session_status() === PHP_SESSION_NONE) {
    session_start();
}

function set_flash_message($message, $type = 'info') {
    $allowed_types = ['success', 'error', 'warning', 'info'];
    $_SESSION['flash_message'] = [
        'message' => (string) $message,
        'type' => in_array($type, $allowed_types, true) ? $type : 'info'
    ];
}

function redirect_with_flash($location, $message, $type = 'info') {
    set_flash_message($message, $type);
    header("Location: " . $location);
    exit();
}

function consume_flash_message() {
    if (empty($_SESSION['flash_message'])) {
        return null;
    }

    $flash = $_SESSION['flash_message'];
    unset($_SESSION['flash_message']);
    return $flash;
}

function render_flash_message() {
    $flash = consume_flash_message();
    if (!$flash) {
        return;
    }

    $type = $flash['type'] ?? 'info';
    $message = $flash['message'] ?? '';
    $icons = [
        'success' => 'fa-circle-check',
        'error' => 'fa-circle-exclamation',
        'warning' => 'fa-triangle-exclamation',
        'info' => 'fa-circle-info'
    ];
    $titles = [
        'success' => 'Tudo certo',
        'error' => 'Atenção',
        'warning' => 'Confirme os dados',
        'info' => 'Aviso'
    ];
    $icon = $icons[$type] ?? $icons['info'];
    $title = $titles[$type] ?? $titles['info'];
    ?>
    <style>
        .dt-flash-toast {
            position: fixed;
            top: 92px;
            right: 24px;
            width: min(380px, calc(100vw - 32px));
            z-index: 99999;
            display: grid;
            grid-template-columns: 42px 1fr 34px;
            gap: 14px;
            align-items: center;
            padding: 16px;
            border-radius: 14px;
            color: #ffffff;
            background: rgba(17, 20, 29, 0.96);
            border: 1px solid rgba(56, 189, 248, 0.24);
            box-shadow: 0 22px 70px rgba(0, 0, 0, 0.55);
            backdrop-filter: blur(14px);
            transform: translateY(-12px);
            opacity: 0;
            pointer-events: none;
            transition: opacity 0.25s ease, transform 0.25s ease;
        }

        .dt-flash-toast.show {
            opacity: 1;
            transform: translateY(0);
            pointer-events: auto;
        }

        .dt-flash-toast.success {
            border-color: rgba(74, 222, 128, 0.38);
        }

        .dt-flash-toast.error {
            border-color: rgba(248, 113, 113, 0.42);
        }

        .dt-flash-toast.warning {
            border-color: rgba(251, 191, 36, 0.42);
        }

        .dt-flash-icon {
            width: 42px;
            height: 42px;
            border-radius: 12px;
            display: flex;
            align-items: center;
            justify-content: center;
            color: #05070a;
            background: linear-gradient(135deg, #38bdf8, #4ade80);
        }

        .dt-flash-toast.error .dt-flash-icon {
            color: #ffffff;
            background: linear-gradient(135deg, #ef4444, #f97316);
        }

        .dt-flash-toast.warning .dt-flash-icon {
            color: #111827;
            background: linear-gradient(135deg, #fbbf24, #38bdf8);
        }

        .dt-flash-copy strong {
            display: block;
            margin-bottom: 4px;
            font-size: 14px;
        }

        .dt-flash-copy p {
            margin: 0;
            color: #cbd5e1;
            font-size: 13px;
            line-height: 1.4;
        }

        .dt-flash-close {
            width: 34px;
            height: 34px;
            border: 1px solid rgba(148, 163, 184, 0.2);
            border-radius: 10px;
            background: rgba(255, 255, 255, 0.04);
            color: #94a3b8;
            cursor: pointer;
        }

        .dt-flash-close:hover {
            border-color: #38bdf8;
            color: #ffffff;
        }

        @media (max-width: 640px) {
            .dt-flash-toast {
                top: 18px;
                right: 16px;
                left: 16px;
                width: auto;
            }
        }
    </style>

    <div class="dt-flash-toast <?php echo htmlspecialchars($type); ?>" id="dt-flash-toast" role="status" aria-live="polite">
        <div class="dt-flash-icon">
            <i class="fa-solid <?php echo htmlspecialchars($icon); ?>"></i>
        </div>
        <div class="dt-flash-copy">
            <strong><?php echo htmlspecialchars($title); ?></strong>
            <p><?php echo htmlspecialchars($message); ?></p>
        </div>
        <button type="button" class="dt-flash-close" aria-label="Fechar aviso" data-flash-close>
            <i class="fa-solid fa-xmark"></i>
        </button>
    </div>

    <script>
        document.addEventListener('DOMContentLoaded', function() {
            const toast = document.getElementById('dt-flash-toast');
            if (!toast) return;

            const close = function() {
                toast.classList.remove('show');
            };

            requestAnimationFrame(function() {
                toast.classList.add('show');
            });

            const closeButton = toast.querySelector('[data-flash-close]');
            if (closeButton) {
                closeButton.addEventListener('click', close);
            }

            setTimeout(close, 5200);
        });
    </script>
    <?php
}
?>
