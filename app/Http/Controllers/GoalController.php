<?php

namespace App\Http\Controllers;

use App\Enums\MessageType;
use App\Http\Requests\GoalRequest;
use App\Http\Resources\GoalResource;
use App\Models\Balance;
use App\Models\Goal;
use Illuminate\Http\RedirectResponse;
use Illuminate\Http\Request;
use Illuminate\Routing\Controllers\HasMiddleware;
use Illuminate\Routing\Controllers\Middleware;
use Illuminate\Support\Facades\Auth;
use Inertia\Response;
use Throwable;
use function App\Helpers\flashMessage;

class GoalController extends Controller implements HasMiddleware
{
    public static function middleware()
    {
        return [
            new Middleware('auth'),
            new Middleware('can:update,goal', only: ['edit', 'update']),
            new Middleware('can:update,goal', only: ['destroy']),
        ];
    }

    public function index(): Response{
        $goals = Goal::query()
        ->select(['id', 'user_id', 'name', 'percentage', 'nominal', 'monthly_saving', 'deadline', 'beginning_balance', 'created_at'])
        ->where('user_id', Auth::id())
        ->filter(request()->only(['search']))
        ->sorting(request()->only(['field', 'direction']))
        ->paginate(request()->load ?? 10);

        return inertia('Saving/Index', [
            'pageSettings' => fn() => [
                'title' => 'Tujuan Menabung',
                'subtitle' => 'Menabung untuk pendidikan, Liburan, atau Investasi Masa Depan',
                'banner' => [
                    'title' => 'Tabungan',
                    'subtitle' => 'Wujudkan impian dengan menabung. Langkah kecil menuju cita-cita yang besar'
                ],
            ],
            'goals' => fn() => GoalResource::collection($goals)->additional([
                'meta' => [
                    'has_pages' => $goals->hasPages()
                ],
            ]),
            'state' => fn() => [
                'page' => request()->page ?? 1,
                'search' => request()->search ?? '',
                'load' => 10,
            ],
            'items' => fn() => [
                ['label' => 'Cuan+', 'href' => route('dashboard')],
                ['label' => 'Tabungan'],
            ],
            'year' => fn() => now()->year,
            'count' => fn() => [
                'countGoal' => fn() => Goal::query()->where('user_id', Auth::id())->count(),
                'countGoalAchieved' => fn() => Goal::query()->where('user_id', Auth::id())->where('percentage', 100)->count(),
                'countGoalNotAchieved' => fn() => Goal::query()->where('user_id', Auth::id())->where('percentage', '<', 100)->count(),
                'countBalance' => fn() => Balance::query()->whereHas('goal', fn($query) => $query->where('user_id', Auth::id()))->sum('amount') + Goal::query()->where('user_id', Auth::id())->sum('beginning_balance')
            ]
        ]);
    }

    public function create(): Response{
        return inertia('Saving/Create', [
            'pageSettings' => fn () => [
                'title' => 'Mulai tetapkan tujuan sekarang',
                'subtitle' => 'Dengan tujuan yang jelas, setiap langkah kecil menabung membawa anda lebih dekat ke impian besar anda',
                'method' => 'POST',
                'action' => route('goals.create'),
            ],
            'items' => fn() => [
                ['label' => 'Cuan+', 'href' => route('dashboard')],
                ['label' => 'Tabungan', 'href' => route('goals.index')],
                ['label' => 'Tambah Tujuan Menabung'],
            ],
        ]);
    }

    public function store(GoalRequest $request): RedirectResponse{
        try{
            Goal::create([
                'user_id' => Auth::id(),
                'name' => $request->name,
                'nominal' => $request->nominal,
                'monthly_saving' => $request->monthly_saving,
                'deadline' => $request->deadline,
                'beginning_balance' => $request->beginning_balance
            ]);

            flashMessage(MessageType::CREATED->message('Tujuan'));
            return to_route('goals.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('goals.index');
        }
    }

    public function edit(Goal $goal): Response{
        return inertia('Saving/Edit', [
            'pageSettings' => fn () => [
                'title' => 'Mulai tetapkan tujuan sekarang',
                'subtitle' => 'Dengan tujuan yang jelas, setiap langkah kecil menabung membawa anda lebih dekat ke impian besar anda',
                'method' => 'PUT',
                'action' => route('goals.update', $goal),
            ],
            'goal' => fn() => $goal,
            'items' => fn() => [
                ['label' => 'Cuan+', 'href' => route('dashboard')],
                ['label' => 'Tabungan', 'href' => route('goals.index')],
                ['label' => 'Perbarui Tujuan Menabung'],
            ],
        ]);
    }

    public function update(Goal $goal, GoalRequest $request): RedirectResponse{
        try{
            $goal->update([
                'name' => $request->name,
                'nominal' => $request->nominal,
                'monthly_saving' => $request->monthly_saving,
                'deadline' => $request->deadline,
                'beginning_balance' => $request->beginning_balance,
                'percentage' => $goal->calculatePercentage($request->beginning_balance, $request->nominal, Auth::id())
            ]);

            flashMessage(MessageType::UPDATED->message('Tujuan'));
            return to_route('goals.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('goals.index');
        }
    }

    public function destroy(Goal $goal): RedirectResponse{
        try{
            $goal->delete();

            flashMessage(MessageType::DELETED->message('Tujuan'));
            return to_route('goals.index');
        } catch (Throwable $e) {
            flashMessage(MessageType::ERROR->message(error: $e->getMessage()), 'error');
            return to_route('goals.index');
        }
    }
}
