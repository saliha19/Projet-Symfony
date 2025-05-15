<?php

namespace App\Tests\Controller;

use App\Entity\Client;
use Doctrine\ORM\EntityManagerInterface;
use Doctrine\ORM\EntityRepository;
use Symfony\Bundle\FrameworkBundle\KernelBrowser;
use Symfony\Bundle\FrameworkBundle\Test\WebTestCase;

final class ClientControllerTest extends WebTestCase
{
    private KernelBrowser $client;
    private EntityManagerInterface $manager;
    private EntityRepository $clientRepository;
    private string $path = '/client/';

    protected function setUp(): void
    {
        $this->client = static::createClient();
        $this->manager = static::getContainer()->get('doctrine')->getManager();
        $this->clientRepository = $this->manager->getRepository(Client::class);

        foreach ($this->clientRepository->findAll() as $object) {
            $this->manager->remove($object);
        }

        $this->manager->flush();
    }

    public function testIndex(): void
    {
        $this->client->followRedirects();
        $crawler = $this->client->request('GET', $this->path);

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client index');

        // Use the $crawler to perform additional assertions e.g.
        // self::assertSame('Some text on the page', $crawler->filter('.p')->first());
    }

    public function testNew(): void
    {
        $this->markTestIncomplete();
        $this->client->request('GET', sprintf('%snew', $this->path));

        self::assertResponseStatusCodeSame(200);

        $this->client->submitForm('Save', [
            'client[nomGerant]' => 'Testing',
            'client[prenomGerant]' => 'Testing',
            'client[raisonSociale]' => 'Testing',
            'client[telephone]' => 'Testing',
            'client[adresse]' => 'Testing',
            'client[ville]' => 'Testing',
            'client[pays]' => 'Testing',
        ]);

        self::assertResponseRedirects($this->path);

        self::assertSame(1, $this->clientRepository->count([]));
    }

    public function testShow(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNomGerant('My Title');
        $fixture->setPrenomGerant('My Title');
        $fixture->setRaisonSociale('My Title');
        $fixture->setTelephone('My Title');
        $fixture->setAdresse('My Title');
        $fixture->setVille('My Title');
        $fixture->setPays('My Title');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));

        self::assertResponseStatusCodeSame(200);
        self::assertPageTitleContains('Client');

        // Use assertions to check that the properties are properly displayed.
    }

    public function testEdit(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNomGerant('Value');
        $fixture->setPrenomGerant('Value');
        $fixture->setRaisonSociale('Value');
        $fixture->setTelephone('Value');
        $fixture->setAdresse('Value');
        $fixture->setVille('Value');
        $fixture->setPays('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s/edit', $this->path, $fixture->getId()));

        $this->client->submitForm('Update', [
            'client[nomGerant]' => 'Something New',
            'client[prenomGerant]' => 'Something New',
            'client[raisonSociale]' => 'Something New',
            'client[telephone]' => 'Something New',
            'client[adresse]' => 'Something New',
            'client[ville]' => 'Something New',
            'client[pays]' => 'Something New',
        ]);

        self::assertResponseRedirects('/client/');

        $fixture = $this->clientRepository->findAll();

        self::assertSame('Something New', $fixture[0]->getNomGerant());
        self::assertSame('Something New', $fixture[0]->getPrenomGerant());
        self::assertSame('Something New', $fixture[0]->getRaisonSociale());
        self::assertSame('Something New', $fixture[0]->getTelephone());
        self::assertSame('Something New', $fixture[0]->getAdresse());
        self::assertSame('Something New', $fixture[0]->getVille());
        self::assertSame('Something New', $fixture[0]->getPays());
    }

    public function testRemove(): void
    {
        $this->markTestIncomplete();
        $fixture = new Client();
        $fixture->setNomGerant('Value');
        $fixture->setPrenomGerant('Value');
        $fixture->setRaisonSociale('Value');
        $fixture->setTelephone('Value');
        $fixture->setAdresse('Value');
        $fixture->setVille('Value');
        $fixture->setPays('Value');

        $this->manager->persist($fixture);
        $this->manager->flush();

        $this->client->request('GET', sprintf('%s%s', $this->path, $fixture->getId()));
        $this->client->submitForm('Delete');

        self::assertResponseRedirects('/client/');
        self::assertSame(0, $this->clientRepository->count([]));
    }
}
