<?php

namespace App\Controllers;

use App\Controllers\BaseController;
use CodeIgniter\HTTP\ResponseInterface;
use App\Services\OperateurService;

class OperateurController extends BaseController
{
    protected $operateurService;

    public function __construct()
    {
        $this->operateurService = service('operateurService');
    }

    public function index(): string
    {
        return view('operateur/dashboard');
    }

    // Prefixes

    public function prefixes(): string
    {
        return view('operateur/prefixes', [
            'prefixes' => $this->operateurService->getAllPrefixes(),
            'operateurs' => $this->operateurService->getAllOperateurs(),
        ]);
    }

    public function prefixesAdd()
    {
        $rules = [
            'nom' => [
                'rules' => 'required|regex_match[/^[0-9]{2,4}$/]|is_unique[prefix.nom]',
                'errors' => [
                    'required' => 'Le prefixe est obligatoire.',
                    'regex_match' => 'Le prefixe doit contenir entre 2 et 4 chiffres (ex: 033).',
                    'is_unique' => 'Ce prefixe existe deja.',
                ],
            ],
            'operateurId' => [
                'rules' => 'required|numeric|is_not_unique[operateur.id]',
                'errors' => [
                    'required' => "L'operateur est obligatoire.",
                    'numeric' => "L'operateur selectionne est invalide.",
                    'is_not_unique' => "L'operateur selectionne n'existe pas.",
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->operateurService->addPrefix(
            $this->request->getPost('nom'),
            (int) $this->request->getPost('operateurId')
        );
        return redirect()->to('/admin/prefixes')->with('reports', [$result['message']]);
    }

    public function prefixesDelete(int $id)
    {
        if (!$this->operateurService->getPrefixById($id)) {
            return redirect()->to('/admin/prefixes')->with('reports', ['Prefixe introuvable.']);
        }
        $this->operateurService->deletePrefix($id);
        return redirect()->to('/admin/prefixes')->with('reports', ['Prefixe supprime.']);
    }

    // Types de transaction

    public function types(): string
    {
        return view('operateur/types', [
            'types' => $this->operateurService->getAllTypes(),
        ]);
    }

    public function typesAdd()
    {
        $rules = [
            'nom' => [
                'rules' => 'required|min_length[2]|max_length[50]|alpha_space|is_unique[typeTransaction.nom]',
                'errors' => [
                    'required' => 'Le nom du type est obligatoire.',
                    'min_length' => 'Le nom doit contenir au moins 2 caracteres.',
                    'max_length' => 'Le nom ne peut pas depasser 50 caracteres.',
                    'alpha_space' => 'Le nom ne peut contenir que des lettres et des espaces.',
                    'is_unique' => 'Ce type existe deja.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->operateurService->addType($this->request->getPost('nom'));
        return redirect()->to('/admin/types')->with('reports', [$result['message']]);
    }

    // Baremes de frais

    public function frais(): string
    {
        return view('operateur/frais', [
            'frais' => $this->operateurService->getAllFrais(),
            'types' => $this->operateurService->getAllTypes(),
        ]);
    }

    private function fraisRules(): array
    {
        return [
            'typeTransactionId' => [
                'rules' => 'required|numeric|is_not_unique[typeTransaction.id]',
                'errors' => [
                    'required' => "Le type d'operation est obligatoire.",
                    'numeric' => "Le type d'operation est invalide.",
                    'is_not_unique' => "Ce type d'operation n'existe pas.",
                ],
            ],
            'minimum' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Le montant minimum est obligatoire.',
                    'numeric' => 'Le montant minimum doit etre un nombre.',
                    'greater_than_equal_to' => 'Le montant minimum ne peut pas etre negatif.',
                ],
            ],
            'maximum' => [
                'rules' => 'required|numeric|greater_than[0]',
                'errors' => [
                    'required' => 'Le montant maximum est obligatoire.',
                    'numeric' => 'Le montant maximum doit etre un nombre.',
                    'greater_than' => 'Le montant maximum doit etre positif.',
                ],
            ],
            'valeur' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]',
                'errors' => [
                    'required' => 'Le frais est obligatoire.',
                    'numeric' => 'Le frais doit etre un nombre.',
                    'greater_than_equal_to' => 'Le frais ne peut pas etre negatif.',
                ],
            ],
        ];
    }

    public function fraisAdd()
    {
        if (!$this->validate($this->fraisRules())) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->operateurService->addFrais(
            (int) $this->request->getPost('typeTransactionId'),
            (float) $this->request->getPost('minimum'),
            (float) $this->request->getPost('maximum'),
            (float) $this->request->getPost('valeur')
        );
        return redirect()->to('/admin/frais')->with('reports', [$result['message']]);
    }

    public function fraisEdit(int $id): string
    {
        $frais = $this->operateurService->getFraisById($id);
        if (!$frais) {
            return redirect()->to('/admin/frais')->with('reports', ['Bareme introuvable.']);
        }

        return view('operateur/frais_edit', [
            'frais' => $frais,
            'types' => $this->operateurService->getAllTypes(),
        ]);
    }

    public function fraisEditSubmit(int $id)
    {
        if (!$this->operateurService->getFraisById($id)) {
            return redirect()->to('/admin/frais')->with('reports', ['Bareme introuvable.']);
        }

        if (!$this->validate($this->fraisRules())) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->operateurService->updateFrais(
            $id,
            (int) $this->request->getPost('typeTransactionId'),
            (float) $this->request->getPost('minimum'),
            (float) $this->request->getPost('maximum'),
            (float) $this->request->getPost('valeur')
        );
        return redirect()->to('/admin/frais')->with('reports', [$result['message']]);
    }

    public function fraisDelete(int $id)
    {
        if (!$this->operateurService->getFraisById($id)) {
            return redirect()->to('/admin/frais')->with('reports', ['Bareme introuvable.']);
        }
        $this->operateurService->deleteFrais($id);
        return redirect()->to('/admin/frais')->with('reports', ['Bareme supprime.']);
    }

    public function gains(): string
    {
        return view('operateur/gains', $this->operateurService->getSituationGains());
    }

    public function clients(): string
    {
        return view('operateur/clients', [
            'clients' => $this->operateurService->getSituationComptesClients(),
        ]);
    }

    // Commissions inter-operateurs

    public function commissions(): string
    {
        return view('operateur/commissions', [
            'commissions' => $this->operateurService->getAllCommissions(),
            'operateurs' => $this->operateurService->getAllOperateurs(),
        ]);
    }

    public function commissionsAdd()
    {
        $rules = [
            'source' => [
                'rules' => 'required|numeric|is_not_unique[operateur.id]',
                'errors' => [
                    'required' => "L'operateur source est obligatoire.",
                    'numeric' => "L'operateur source est invalide.",
                    'is_not_unique' => "L'operateur source n'existe pas.",
                ],
            ],
            'destinataire' => [
                'rules' => 'required|numeric|is_not_unique[operateur.id]',
                'errors' => [
                    'required' => "L'operateur destinataire est obligatoire.",
                    'numeric' => "L'operateur destinataire est invalide.",
                    'is_not_unique' => "L'operateur destinataire n'existe pas.",
                ],
            ],
            'pourcentage' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
                'errors' => [
                    'required' => 'Le pourcentage est obligatoire.',
                    'numeric' => 'Le pourcentage doit etre un nombre.',
                    'greater_than_equal_to' => 'Le pourcentage doit etre au moins 0.',
                    'less_than_equal_to' => 'Le pourcentage ne peut pas depasser 100.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->operateurService->addCommission(
            (int) $this->request->getPost('source'),
            (int) $this->request->getPost('destinataire'),
            (float) $this->request->getPost('pourcentage')
        );
        return redirect()->to('/admin/commissions')->with('reports', [$result['message']]);
    }

    public function commissionsEdit(int $id): string
    {
        $commission = $this->operateurService->getCommissionById($id);
        if (!$commission) {
            return redirect()->to('/admin/commissions')->with('reports', ['Commission introuvable.']);
        }

        return view('operateur/commissions_edit', [
            'commission' => $commission,
            'operateurs' => $this->operateurService->getAllOperateurs(),
        ]);
    }

    public function commissionsEditSubmit(int $id)
    {
        if (!$this->operateurService->getCommissionById($id)) {
            return redirect()->to('/admin/commissions')->with('reports', ['Commission introuvable.']);
        }

        $rules = [
            'source' => [
                'rules' => 'required|numeric|is_not_unique[operateur.id]',
                'errors' => [
                    'required' => "L'operateur source est obligatoire.",
                    'numeric' => "L'operateur source est invalide.",
                    'is_not_unique' => "L'operateur source n'existe pas.",
                ],
            ],
            'destinataire' => [
                'rules' => 'required|numeric|is_not_unique[operateur.id]',
                'errors' => [
                    'required' => "L'operateur destinataire est obligatoire.",
                    'numeric' => "L'operateur destinataire est invalide.",
                    'is_not_unique' => "L'operateur destinataire n'existe pas.",
                ],
            ],
            'pourcentage' => [
                'rules' => 'required|numeric|greater_than_equal_to[0]|less_than_equal_to[100]',
                'errors' => [
                    'required' => 'Le pourcentage est obligatoire.',
                    'numeric' => 'Le pourcentage doit etre un nombre.',
                    'greater_than_equal_to' => 'Le pourcentage doit etre au moins 0.',
                    'less_than_equal_to' => 'Le pourcentage ne peut pas depasser 100.',
                ],
            ],
        ];
        if (!$this->validate($rules)) {
            return redirect()->back()->withInput()->with('reports', $this->validator->getErrors());
        }

        $result = $this->operateurService->updateCommission(
            $id,
            (int) $this->request->getPost('source'),
            (int) $this->request->getPost('destinataire'),
            (float) $this->request->getPost('pourcentage')
        );
        return redirect()->to('/admin/commissions')->with('reports', [$result['message']]);
    }

    public function commissionsDelete(int $id)
    {
        if (!$this->operateurService->getCommissionById($id)) {
            return redirect()->to('/admin/commissions')->with('reports', ['Commission introuvable.']);
        }
        $this->operateurService->deleteCommission($id);
        return redirect()->to('/admin/commissions')->with('reports', ['Commission supprimee.']);
    }
}
