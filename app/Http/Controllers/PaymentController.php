<?php

namespace App\Http\Controllers;

use App\Enums\MessageType;
use App\Enums\PaymentType;
use App\Http\Requests\PaymentRequest;
use App\Http\Resources\PaymentResource;
use App\Models\Payment;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Throwable;

use function App\Helpers\flashMessage;

class PaymentController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth'),
            new Middleware('can:update.payment', only: (['edit', 'update'])),
             new Middleware('can:delete.payment', only: (['destroy'])),
        ];
    }

    public function index(): Response{
        $payments = Payment::query()
        ->select(['id', 'user_id', 'name', 'type', 'account_number', 'account_owner', 'created_at'])
        ->where('user_id', Auth::id())
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Payments/Index', [
            'pageSettings' => fn() => [
                'title' => 'Metode Pembayaran',
                'subtitle' => 'Menampilkan semua data metode pembayaran yang tersedia pada akun anda',
            ],
            'payments' => fn() => PaymentResource::collection($payments)->additional([
                'meta' => [
                    'has_pages' => $payments->hasPages()
                ],
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
            'items' => fn() => [
                ['label' => 'Cuan+', 'href' => route('dashboard')],
                ['label' => 'Metode Pembayaran'],
            ],
        ]);
    }

    public function create(): Response{
        return inertia('Payments/Create', [
            'pageSettings' => fn () => [
                'title' => 'Tambah Metode Pembayaran',
                'subtitle' => 'Buat metode pembayaran baru disini. Klik simpan setelah selesai',
                'method' => 'POST',
                'action' => route('payments.create'),
            ],
            'items' => fn() => [
                ['label' => 'Cuan+', 'href' => route('dashboard')],
                ['label' => 'Metode Pembayaran', 'href' => route('payments.index')],
                ['label' => 'Tambah Metode Pembayaran'],
            ],
            'paymentTypes' => fn() => PaymentType::options(),
        ]);
    }

    public function store(PaymentRequest $request): RedirectResponse{
        try{
            Payment::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'type' => $request->type,
                'account_number' => $request->account_number,
                'account_owner' => $request->account_owner
            ]);

            flashMessage(MessageType::CREATED->message('Metode Pembayaran'));
            return to_route('payments.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('payments.index');
        }
    }

    public function edit(Payment $payment): Response{
        return inertia('Payments/Edit', [
            'pageSettings' => fn () => [
                'title' => 'Edit Metode Pembayaran',
                'subtitle' => 'Edit metode pembayaran disini. Klik simpan setelah selesai',
                'method' => 'PUT',
                'action' => route('payments.update', $payment),
            ],
            'payment' => fn() => $payment,
            'items' => fn() => [
                ['label' => 'Cuan+', 'href' => route('dashboard')],
                ['label' => 'Metode Pembayaran', 'href' => route('payments.index')],
                ['label' => 'Tambah Metode Pembayaran'],
            ],
            'paymentTypes' => fn() => PaymentType::options(),
        ]);
    }

    public function update(Payment $payment, PaymentRequest $request): RedirectResponse{
        try{
            $payment->update([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'type' => $request->type,
                'account_number' => $request->type == PaymentType::CASH->value ? null : ($request->account_number ?? $payment->account_number),
                'account_owner' => $request->account_owner
            ]);

            flashMessage(MessageType::UPDATED->message('Metode Pembayaran'));
            return to_route('payments.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('payments.index');
        }
    }

    public function destroy(Payment $payment): RedirectResponse{
        try{
            $payment->delete();

            flashMessage(MessageType::DELETED->message('Metode Pembayaran'));
            return to_route('payments.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('payments.index');
        }
    }
}
