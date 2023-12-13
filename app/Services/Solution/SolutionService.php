<?php

namespace App\Services\Solution;

use App\Models\Solution;
use Illuminate\Http\UploadedFile;
use Illuminate\Support\Facades\Storage;

class SolutionService
{
    public function saveSolutionWithURL(string $url, int $userId, int $taskId): bool
    {
        $solutionUploaded = Solution::query()
            ->where('user_id', $userId)
            ->where('task_id', $taskId)
            ->exists();

        if ($solutionUploaded) {
            return false;
        }

        Solution::query()
            ->create([
                'user_id' => $userId,
                'task_id' => $taskId,
                'url' => $url
            ]);

        return true;
    }

    public function uploadSolution(UploadedFile $file, int $userId, int $taskId): bool
    {
        $solutionUploaded = Solution::query()
            ->where('user_id', $userId)
            ->where('task_id', $taskId)
            ->exists();

        if ($solutionUploaded) {
            return false;
        }

        Solution::query()
            ->create([
                'user_id' => $userId,
                'task_id' => $taskId,
                'file' => Storage::disk('public_uploads')->put('solutions', $file)
            ]);

        return true;
    }
}
