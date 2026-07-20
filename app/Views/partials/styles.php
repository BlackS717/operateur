<link rel="preconnect" href="https://fonts.googleapis.com">
<link href="https://fonts.googleapis.com/css2?family=Inter:wght@400;500;600;700;800&display=swap" rel="stylesheet">
<style>
    :root {
        --brand-1: #4f46e5;
        --brand-2: #7c3aed;
        --brand-3: #06b6d4;
        --ink: #1e1b2e;
        --muted: #6b7280;
        --surface: #ffffff;
        --bg: #f4f5fb;
        --radius: 16px;
        --shadow: 0 10px 30px -12px rgba(79, 70, 229, 0.18);
    }

    * { box-sizing: border-box; }

    body {
        font-family: 'Inter', 'Segoe UI', sans-serif;
        background: var(--bg);
        color: var(--ink);
        min-height: 100vh;
    }

    h1 {
        font-weight: 800;
        letter-spacing: -0.02em;
        margin-bottom: 1.25rem;
    }

    /* Navbar */
    .app-navbar {
        background: linear-gradient(120deg, var(--brand-1), var(--brand-2));
        box-shadow: 0 8px 24px -10px rgba(79, 70, 229, 0.45);
        padding-top: .65rem;
        padding-bottom: .65rem;
    }
    .app-navbar .navbar-brand {
        font-weight: 800;
        letter-spacing: -0.01em;
    }
    .app-navbar .nav-link {
        font-weight: 500;
        opacity: .88;
        border-radius: 999px;
        padding: .4rem .9rem !important;
        transition: background .15s ease, opacity .15s ease;
    }
    .app-navbar .nav-link:hover {
        opacity: 1;
        background: rgba(255, 255, 255, 0.14);
    }

    /* Cards */
    .card {
        border: none;
        border-radius: var(--radius);
        box-shadow: var(--shadow);
    }
    .card .card-title {
        font-weight: 700;
    }

    /* Buttons */
    .btn {
        border-radius: 10px;
        font-weight: 600;
        letter-spacing: -0.01em;
    }
    .btn-primary {
        background: linear-gradient(120deg, var(--brand-1), var(--brand-2));
        border: none;
        box-shadow: 0 6px 16px -6px rgba(79, 70, 229, 0.55);
    }
    .btn-primary:hover {
        background: linear-gradient(120deg, #4338ca, #6d28d9);
    }
    .btn-dark {
        background: var(--ink);
        border: none;
    }
    .btn-sm { border-radius: 8px; }

    /* Forms */
    .form-control, .form-select {
        border-radius: 10px;
        border: 1px solid #e3e4ee;
        padding: .55rem .85rem;
    }
    .form-control:focus, .form-select:focus {
        border-color: var(--brand-1);
        box-shadow: 0 0 0 4px rgba(79, 70, 229, 0.14);
    }
    .form-label {
        font-weight: 600;
        font-size: .9rem;
        color: var(--muted);
    }

    /* Tables */
    .table {
        background: var(--surface);
        border-radius: var(--radius);
        overflow: hidden;
        box-shadow: var(--shadow);
    }
    .table thead {
        background: #f0f0fb;
    }
    .table thead th {
        border-bottom: none;
        font-size: .78rem;
        text-transform: uppercase;
        letter-spacing: .04em;
        color: var(--muted);
        font-weight: 700;
        padding: .9rem 1rem;
    }
    .table tbody td {
        padding: .85rem 1rem;
        vertical-align: middle;
        border-color: #eef0f6;
    }
    .table-striped > tbody > tr:nth-of-type(odd) > * {
        background-color: #fafaff;
    }

    /* Alerts */
    .alert {
        border: none;
        border-radius: 12px;
    }

    /* Auth pages */
    .auth-shell {
        min-height: 100vh;
        display: flex;
        align-items: center;
        justify-content: center;
        background: radial-gradient(circle at top left, rgba(79,70,229,0.16), transparent 45%),
                    radial-gradient(circle at bottom right, rgba(6,182,212,0.16), transparent 45%),
                    var(--bg);
        padding: 1.5rem;
    }
    .auth-card {
        width: 100%;
        max-width: 400px;
        background: var(--surface);
        border-radius: 20px;
        box-shadow: var(--shadow);
        padding: 2.25rem 2rem;
    }
    .auth-card h1 {
        font-size: 1.5rem;
        margin-bottom: .35rem;
    }
    .auth-subtitle {
        color: var(--muted);
        font-size: .9rem;
        margin-bottom: 1.5rem;
    }
    .auth-switch {
        text-align: center;
        margin-top: 1.25rem;
        font-size: .88rem;
        color: var(--muted);
    }
    .auth-switch a {
        color: var(--brand-1);
        font-weight: 600;
        text-decoration: none;
    }
    .auth-switch a:hover { text-decoration: underline; }

    .container { max-width: 1100px; }
</style>
