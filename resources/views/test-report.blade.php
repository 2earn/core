<!DOCTYPE html>
<html lang="en">
<head>
    <meta charset="UTF-8">
    <meta name="viewport" content="width=device-width, initial-scale=1.0">
    <title>Test Report - {{ config('app.name', '2earn') }}</title>
    <link rel="stylesheet" href="{{ asset('css/test-report.css') }}">
</head>
<body>
    <div class="container">
        {{-- Header Section --}}
        <div class="header">
            <h1>🧪 Test Report</h1>
            <p class="subtitle">{{ config('app.name', '2earn Application') }}</p>
            <p class="timestamp">Generated on: {{ $stats['timestamp'] }}</p>
        </div>

        {{-- Statistics Grid --}}
        <div class="stats-grid">
            <div class="stat-card total">
                <span class="icon">📊</span>
                <div class="label">Total Tests</div>
                <div class="number">{{ $stats['total'] }}</div>
            </div>

            <div class="stat-card passed">
                <span class="icon">✅</span>
                <div class="label">Passed</div>
                <div class="number">{{ $stats['passed'] }}</div>
            </div>

            <div class="stat-card failed">
                <span class="icon">❌</span>
                <div class="label">Failed</div>
                <div class="number">{{ $stats['failures'] }}</div>
            </div>

            <div class="stat-card skipped">
                <span class="icon">⏭️</span>
                <div class="label">Skipped</div>
                <div class="number">{{ $stats['skipped'] }}</div>
            </div>

            <div class="stat-card rate">
                <span class="icon">📈</span>
                <div class="label">Success Rate</div>
                <div class="number">{{ $stats['success_rate'] }}%</div>
            </div>

            <div class="stat-card time">
                <span class="icon">⏱️</span>
                <div class="label">Total Time</div>
                <div class="number">{{ round($stats['time'], 2) }}s</div>
            </div>
        </div>

        {{-- Progress Bar Section --}}
        <div class="progress-section">
            <div class="progress-bar-container">
                @php
                    $progressClass = '';
                    if ($stats['success_rate'] < 50) {
                        $progressClass = 'low';
                    } elseif ($stats['success_rate'] < 80) {
                        $progressClass = 'medium';
                    }
                @endphp
                <div class="progress-fill {{ $progressClass }}" style="width: {{ $stats['success_rate'] }}%">
                    {{ $stats['success_rate'] }}% Passed
                </div>
            </div>
        </div>

        {{-- Test Suites Section --}}
        <div class="test-suites">
            <h2 class="section-title">Test Suites</h2>

            @forelse($testSuites as $suite)
                <div class="test-suite">
                    <div class="test-suite-header">
                        <div class="test-suite-info">
                            <div class="test-suite-title">
                                {{ $suite['name'] }}
                            </div>
                            @if(!empty($suite['groups']))
                                <div class="test-suite-groups">
                                    @foreach($suite['groups'] as $group)
                                        <span class="group-badge group-{{ $group }}">
                                            {{ $group }}
                                        </span>
                                    @endforeach
                                </div>
                            @endif
                        </div>
                        <div class="test-suite-stats">
                            <span class="test-suite-stat passed">
                                ✓ {{ $suite['passed'] }} passed
                            </span>
                            @if($suite['failures'] > 0)
                                <span class="test-suite-stat failed">
                                    ✗ {{ $suite['failures'] }} failed
                                </span>
                            @endif
                            <span class="test-suite-stat time">
                                ⏱ {{ round($suite['time'], 2) }}s
                            </span>
                        </div>
                    </div>

                    <div class="test-cases">
                        @foreach($suite['test_cases'] as $testCase)
                            <div class="test-case">
                                <div class="test-case-name">
                                    @if($testCase['status'] === 'passed')
                                        <span class="status-icon passed">✓</span>
                                    @elseif($testCase['status'] === 'failed')
                                        <span class="status-icon failed">✗</span>
                                    @else
                                        <span class="status-icon skipped">⊘</span>
                                    @endif
                                    <span>{{ $testCase['name'] }}</span>
                                </div>
                                <div class="test-case-status">
                                    <span class="test-case-time">{{ number_format($testCase['time'], 3) }}s</span>
                                    <span class="status-badge {{ $testCase['status'] }}">
                                        {{ $testCase['status'] }}
                                    </span>
                                </div>
                            </div>
                        @endforeach
                    </div>
                </div>
            @empty
                <div class="alert alert-info">
                    <p>No test suites found.</p>
                </div>
            @endforelse
        </div>

        {{-- Footer Section --}}
        <div class="footer">
            <p><strong>{{ config('app.name', '2earn Application') }}</strong> - Test Suite Report</p>
            <p>Generated automatically by Laravel PHPUnit</p>
            <p style="margin-top: 10px; font-size: 0.9em;">
                Laravel {{ app()->version() }} | PHP {{ PHP_VERSION }}
            </p>
        </div>
    </div>
</body>
</html>
