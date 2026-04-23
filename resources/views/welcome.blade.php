<!DOCTYPE html>
<html lang="{{ str_replace('_', '-', app()->getLocale()) }}">
<head>
    <meta charset="utf-8">
    <meta name="viewport" content="width=device-width, initial-scale=1">
    <title>{{ config('app.name') }}</title>
    <style>
        :root {
            color-scheme: dark;
            --bg: #11130f;
            --panel: #f4f0df;
            --ink: #1b1b18;
            --muted: #66665e;
            --line: #d8d0b5;
            --ok: #0d7c55;
            --bad: #b42318;
            --accent: #e4b946;
        }

        * {
            box-sizing: border-box;
        }

        body {
            margin: 0;
            min-height: 100vh;
            background:
                linear-gradient(135deg, rgba(228, 185, 70, .16), transparent 38%),
                radial-gradient(circle at 82% 18%, rgba(13, 124, 85, .22), transparent 24%),
                var(--bg);
            color: var(--panel);
            font-family: ui-monospace, SFMono-Regular, Menlo, Monaco, Consolas, "Liberation Mono", monospace;
        }

        main {
            width: min(1120px, calc(100% - 32px));
            margin: 0 auto;
            padding: 48px 0;
        }

        .masthead {
            display: grid;
            grid-template-columns: 1.2fr .8fr;
            gap: 24px;
            align-items: end;
            margin-bottom: 24px;
        }

        h1 {
            margin: 0;
            max-width: 760px;
            font-family: Georgia, "Times New Roman", serif;
            font-size: clamp(38px, 7vw, 88px);
            font-weight: 700;
            line-height: .9;
            letter-spacing: 0;
        }

        .stamp {
            justify-self: end;
            border: 1px solid rgba(244, 240, 223, .36);
            padding: 14px 16px;
            text-transform: uppercase;
            font-size: 12px;
            line-height: 1.7;
            color: rgba(244, 240, 223, .74);
        }

        .grid {
            display: grid;
            grid-template-columns: repeat(4, minmax(0, 1fr));
            gap: 14px;
        }

        .metric,
        .table-panel {
            background: var(--panel);
            color: var(--ink);
            border: 1px solid var(--line);
            border-radius: 6px;
            box-shadow: 0 20px 60px rgba(0, 0, 0, .22);
        }

        .metric {
            min-height: 132px;
            padding: 18px;
        }

        .metric strong {
            display: block;
            margin-top: 22px;
            font-size: clamp(24px, 3vw, 36px);
            line-height: 1;
        }

        .label {
            display: block;
            color: var(--muted);
            font-size: 12px;
            text-transform: uppercase;
        }

        .status {
            color: var(--ok);
        }

        .status.bad {
            color: var(--bad);
        }

        .table-panel {
            margin-top: 14px;
            overflow: hidden;
        }

        .table-head {
            display: flex;
            justify-content: space-between;
            gap: 16px;
            padding: 18px;
            border-bottom: 1px solid var(--line);
        }

        table {
            width: 100%;
            border-collapse: collapse;
            font-size: 14px;
        }

        th,
        td {
            padding: 14px 18px;
            border-bottom: 1px solid var(--line);
            text-align: left;
        }

        th {
            color: var(--muted);
            font-size: 11px;
            text-transform: uppercase;
        }

        tr:last-child td {
            border-bottom: 0;
        }

        .error {
            padding: 18px;
            color: var(--bad);
            overflow-wrap: anywhere;
        }

        @media (max-width: 760px) {
            main {
                width: min(100% - 20px, 1120px);
                padding: 28px 0;
            }

            .masthead,
            .grid {
                grid-template-columns: 1fr;
            }

            .stamp {
                justify-self: stretch;
            }

            table {
                display: block;
                overflow-x: auto;
                white-space: nowrap;
            }
        }
    </style>
</head>
<body>
    <main>
        <section class="masthead">
            <h1>Dokploy Laravel smoke test</h1>
            <div class="stamp">
                <div>{{ now()->format('Y-m-d H:i:s T') }}</div>
                <div>{{ request()->getHost() }}</div>
            </div>
        </section>

        <section class="grid" aria-label="Application status">
            <article class="metric">
                <span class="label">Environment</span>
                <strong>{{ app()->environment() }}</strong>
            </article>
            <article class="metric">
                <span class="label">Laravel</span>
                <strong>{{ app()->version() }}</strong>
            </article>
            <article class="metric">
                <span class="label">Database</span>
                <strong class="status {{ $database['ok'] ? '' : 'bad' }}">{{ $database['ok'] ? 'online' : 'offline' }}</strong>
            </article>
            <article class="metric">
                <span class="label">Seeded users</span>
                <strong>{{ number_format($userCount) }}</strong>
            </article>
        </section>

        <section class="table-panel" aria-label="Database details">
            <div class="table-head">
                <span>Connection: {{ $database['connection'] }}</span>
                <span>{{ $database['host'] ?? 'local' }} / {{ $database['name'] }}</span>
            </div>

            @if ($database['ok'])
                <table>
                    <thead>
                        <tr>
                            <th>ID</th>
                            <th>Name</th>
                            <th>Email</th>
                            <th>Created</th>
                        </tr>
                    </thead>
                    <tbody>
                        @foreach ($users as $user)
                            <tr>
                                <td>{{ $user->id }}</td>
                                <td>{{ $user->name }}</td>
                                <td>{{ $user->email }}</td>
                                <td>{{ optional($user->created_at)->toDateTimeString() }}</td>
                            </tr>
                        @endforeach
                    </tbody>
                </table>
            @else
                <div class="error">{{ $database['message'] }}</div>
            @endif
        </section>
    </main>
</body>
</html>
