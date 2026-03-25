<?php

namespace App\Http\Controllers;

use Illuminate\Http\Request;
use App\Services\ReportBuilderService;

use Barryvdh\DomPDF\Facade\Pdf;

class ReportController extends Controller
{
    private ReportBuilderService $reportBuilder;

    public function __construct(ReportBuilderService $reportBuilder)
    {
        $this->reportBuilder = $reportBuilder;
    }

    public function generateJson(Request $request)
    {
        $payload = $request->json()->all();

        if (empty($payload)) {
            return response()->json(['error' => 'No payload provided'], 400);
        }

        $structuredReport = $this->reportBuilder->buildFromPayload($payload);

        return response()->json($structuredReport);
    }
    
    public function generatePdf(Request $request)
    {
        $payload = $request->json()->all();

        if (empty($payload)) {
            return response()->json(['error' => 'No payload provided'], 400);
        }

        $structuredReport = $this->reportBuilder->buildFromPayload($payload);
        
        $pdf = Pdf::loadView('pdf.report', ['report' => $structuredReport]);
        
        return $pdf->download('heuristic_report.pdf');
    }

    private function getDummyPayload(): array
    {
        return [
            'project_name' => 'E-Commerce Checkout Redesign',
            'evaluated_by' => 'UX Team Alpha',
            'version' => '1.2.0',
            'findings' => [
                [
                    'id' => 'FND-001',
                    'title' => 'Missing error message on invalid email',
                    'severity' => 'High',
                    'description' => 'When a user enters an invalid email format, no inline validation error appears until submission.',
                    'evidence' => 'Screenshot of form with "user@com" without highlighting.',
                    'recommendation' => 'Add inline validation on blur event for the email field.',
                    'heuristic_id' => 'H5'
                ],
                [
                    'id' => 'FND-002',
                    'title' => 'Checkout progress is unclear',
                    'severity' => 'Medium',
                    'description' => 'Users do not know how many steps are left in the checkout process.',
                    'evidence' => 'User testing video where 2/5 participants asked "am I almost done?"',
                    'recommendation' => 'Implement a step progress indicator at the top of the checkout view.',
                    'heuristic_id' => 'H1'
                ],
                [
                    'id' => 'FND-003',
                    'title' => 'Cancel button color is too prominent',
                    'severity' => 'Low',
                    'description' => 'The cancel button uses the primary brand color, making it look like the primary action.',
                    'evidence' => 'Visual inspection of the modal.',
                    'recommendation' => 'Change the cancel button to a secondary, less saturated color.',
                    'heuristic_id' => 'H8'
                ],
                [
                    'id' => 'FND-004',
                    'title' => 'Cannot undo removing item from cart',
                    'severity' => 'Critical',
                    'description' => 'If a user accidentally clicks "Remove", the item disappears immediately without confirmation or undo option.',
                    'evidence' => 'Analytics shows 15% of removed items are added back within 3 seconds.',
                    'recommendation' => 'Provide a toast notification with an standard "Undo" action.',
                    'heuristic_id' => 'H3'
                ]
            ]
        ];
    }
}
