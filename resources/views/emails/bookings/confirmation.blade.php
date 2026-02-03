<!DOCTYPE html>
<html>

<head>
    <meta charset="utf-8">
    <title>Epoch Cinema - Booking Confirmation #{{ $booking->id }}</title>
    <style>
        body {
            font-family: Arial, sans-serif;
            line-height: 1.6;
            color: #333;
            max-width: 600px;
            margin: 0 auto;
            padding: 20px;
        }

        .header {
            background: #1a1a1a;
            color: white;
            padding: 20px;
            text-align: center;
        }

        .content {
            padding: 20px 0;
        }

        .qr-section {
            text-align: center;
            margin: 30px 0;
            background: #f9f9f9;
            padding: 20px;
            border-radius: 8px;
        }

        .tickets-container {
            margin: 30px 0;
        }

        .ticket {
            background: linear-gradient(135deg, #fff 0%, #f8f8f8 100%);
            border: 2px solid #333;
            border-radius: 12px;
            margin: 20px 0;
            overflow: hidden;
            box-shadow: 0 8px 25px rgba(0, 0, 0, 0.15);
            position: relative;
            max-width: 380px;
            margin-left: auto;
            margin-right: auto;
        }

        .ticket::before {
            content: '';
            position: absolute;
            top: 0;
            left: -20px;
            right: -20px;
            height: 40px;
            background: repeating-linear-gradient(90deg,
                    transparent,
                    transparent 19px,
                    #333 19px,
                    #333 20px);
            clip-path: polygon(0 0, 100% 0, 100% 100%, 0 100%, 0 25%, 5px 20%, 10px 25%, 100% 25%);
        }

        .ticket-poster {
            height: 140px;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
            position: relative;
            overflow: hidden;
        }

        .ticket-poster::after {
            content: '🎥';
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            font-size: 48px;
            opacity: 0.8;
        }

        .ticket-poster.poster-{{ $screening->movie->slug ?? 'default' }} {
            background-image: url('{{ $screening->movie->poster ?? '' }}');
            background-size: cover;
            background-position: center;
        }

        .ticket-content {
            padding: 20px;
        }

        .movie-info {
            margin-bottom: 15px;
        }

        .movie-title {
            font-size: 18px;
            font-weight: bold;
            color: #1a1a1a;
            margin: 0 0 5px 0;
            line-height: 1.3;
        }

        .movie-era {
            color: #666;
            font-size: 14px;
            text-transform: uppercase;
            letter-spacing: 1px;
        }

        .ticket-details {
            display: grid;
            grid-template-columns: 1fr 1fr;
            gap: 12px;
            margin-bottom: 15px;
            font-size: 14px;
        }

        .detail-item {
            background: #f4f4f4;
            padding: 8px 12px;
            border-radius: 6px;
            text-align: center;
        }

        .detail-label {
            font-size: 11px;
            color: #666;
            display: block;
            margin-bottom: 2px;
            font-weight: 600;
        }

        .detail-value {
            font-weight: bold;
            color: #1a1a1a;
            font-size: 15px;
        }

        .ticket-type {
            background: linear-gradient(135deg, #ff9a9e 0%, #fecfef 100%);
            color: #333;
            padding: 8px 16px;
            border-radius: 20px;
            font-size: 13px;
            font-weight: bold;
            text-transform: uppercase;
            letter-spacing: 0.5px;
            margin-top: 10px;
            display: inline-block;
        }

        .ticket-price {
            position: absolute;
            top: 15px;
            right: 15px;
            background: #1a1a1a;
            color: white;
            padding: 8px 12px;
            border-radius: 6px;
            font-weight: bold;
            font-size: 14px;
        }

        .barcode {
            font-family: 'Courier New', monospace;
            font-size: 22px;
            font-weight: bold;
            letter-spacing: 2px;
            background: #000;
            color: #fff;
            padding: 12px;
            text-align: center;
            margin: 15px 20px 20px;
            border-radius: 4px;
        }

        .footer {
            margin-top: 40px;
            padding-top: 20px;
            border-top: 1px solid #eee;
            font-size: 14px;
            color: #666;
        }

        .qr-placeholder {
            width: 250px;
            height: 250px;
            background: #e0e0e0;
            margin: 20px auto;
            display: flex;
            align-items: center;
            justify-content: center;
            border: 2px dashed #ccc;
            border-radius: 8px;
            font-size: 14px;
            color: #999;
        }

        .ticket-poster {
            height: 140px;
            position: relative;
            overflow: hidden;
            background: linear-gradient(45deg, #667eea 0%, #764ba2 100%);
        }

        .poster-placeholder {
            position: absolute;
            top: 50%;
            left: 50%;
            transform: translate(-50%, -50%);
            text-align: center;
            color: rgba(255, 255, 255, 0.9);
            font-size: 14px;
            font-weight: bold;
            text-shadow: 0 2px 4px rgba(0, 0, 0, 0.5);
        }


        @media (max-width: 600px) {
            body {
                padding: 10px;
            }

            .ticket {
                margin: 15px 0;
                max-width: 100%;
            }

            .barcode {
                font-size: 18px;
            }

            .ticket-details {
                grid-template-columns: 1fr;
            }
        }
    </style>
</head>

<body>
    <div class="header">
        <h1>🎥 Epoch Cinema</h1>
        <p>Booking Confirmed!</p>
    </div>

    <div class="content">
        <h2>Confirmation #{{ $booking->id }}</h2>

        <p><strong>Date:</strong> {{ $booking->created_at->format('M d, Y \a\t g:i A') ?? 'Just now' }}</p>

        <h3>Screening Details</h3>
        <p><strong>Era:</strong> {{ $screening->movie->era->name ?? 'N/A' }}</p>
        <p><strong>Movie:</strong> {{ $screening->movie->title }} ({{ $screening->movie->release_date }})</p>
        <p><strong>Time:</strong> {{ $screening->start_time->format('M d, Y \a\t g:i A') ?? 'TBD' }}</p>
        <p><strong>Theater:</strong> {{ $screening->auditorium->name ?? 'TBD' }}</p>
        <p><strong>Duration:</strong> {{ $screening->movie->runtime_min ?? 'N/A' }} min</p>

        <div class="tickets-container">
            <h3>Your Tickets</h3>
            @foreach ($tickets ?? [] as $ticket)
                <div class="ticket">
                    <div class="ticket-poster">
                        @if ($screening->movie->poster->path ?? false)
                            <img src="{{ $screening->movie->poster->path }}" alt="{{ $screening->movie->title }} poster"
                                style="width: 100%; height: 100%; object-fit: cover;">
                        @else
                            <div class="poster-placeholder">🎥 {{ substr($screening->movie->title, 0, 15) }}</div>
                        @endif
                    </div>
                    <div class="ticket-price">&euro;{{ number_format($ticket['price'], 2) }}</div>

                    <div class="ticket-content">
                        <div class="movie-info">
                            <div class="movie-title">{{ $screening->movie->title }}</div>
                            <div class="movie-era">{{ $screening->movie->era->name ?? 'N/A' }}</div>
                        </div>

                        <div class="ticket-details">
                            <div class="detail-item">
                                <span class="detail-label">Row</span>
                                <span class="detail-value">{{ $ticket['row'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Seat</span>
                                <span class="detail-value">{{ $ticket['seat_number'] }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Auditorium</span>
                                <span class="detail-value">{{ $screening->auditorium->name ?? 'TBD' }}</span>
                            </div>
                            <div class="detail-item">
                                <span class="detail-label">Time</span>
                                <span
                                    class="detail-value">{{ $screening->start_time->format('g:i A') ?? 'TBD' }}</span>
                            </div>
                        </div>

                        <div class="ticket-type">{{ $ticket['name'] ?? 'Standard' }}</div>
                    </div>

                    <div class="barcode">
                        EPCH{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}-{{ $ticket['row'] }}{{ $ticket['seat_number'] }}
                    </div>
                </div>
            @endforeach
        </div>

        {{-- <div class="qr-section">
            <p><strong>Scan this QR code at the entrance</strong></p>
            <div class="qr-placeholder">
                📱 Scan "EPCH{{ str_pad($booking->id, 6, '0', STR_PAD_LEFT) }}"
                <br><small>or show any ticket barcode</small>
            </div>
            <p><em>Generate QR at epoch.com/booking/{{ $booking->id }}/qr</em></p>
        </div> --}}

        @if (isset($email) || $booking->user?->email)
            <p><strong>Email:</strong> {{ $guest_email ?? $booking->user->email }}</p>
        @endif

        <div class="footer">
            <p>Questions? Reply to this email or contact support@epoch.com</p>
            <p>&copy; 2026 Epoch Cinema Platform. Budapest, Hungary.</p>
        </div>
    </div>
</body>

</html>
