# Domain-Driven Design - Doctrine Repository

Now we will implement an actual relational database repository using Doctrine 2 and as always we'll try to test it.

The repository is a persistent collection that we know from [repository article](../ddd_repository/repository.md).
We expect that we know the cart model from [model](../ddd_simplify_model/simplify_model.md) and [implementation](../ddd_implementation/implementation.md) articles.
But it doesn't make any harm if we repeat the cart model again.

![cart aggregate](cart_aggregate.png)

## Relational Model

While talking about the domain design we were thinking in object model all the time.
But if we want to use a relational database, we have to think in relations again.
So we have a cart entity which has zero or more item entities.
Of course, both entities have couple of their own attributes.

![cart has items ER diagram](cart_er.png)

### Independent Item

The relation between the item and the cart is many-to-one.
This relation needs the item to know which cart it is in.
But we have a different approach in our model.
The cart knows about the item but the item has no idea about the cart.
This thinking changes the relation into many-to-many.

![cart has items ER diagram with joining table](cart_er_independent.png)

This model allows us to use items as a collection in PHP.
The original model would force us to pass the cart into the item.
It would change the domain model and that is something we want to avoid as long as it is possible.

## Doctrine Influence on Object Model

The Doctrine allows us to store pure domain objects, which si great.
But still, it has an influence on the implemented model.

### Identifier

All persisted entities must have an identifier, an identifier which is unique in the whole system.
This requirement influences the cart item which doesn't have an identifier yet.
We know that inside the cart the item is identified by the product identifier.
But there can be items in different carts that hold the same product identifier.
The product doesn't identify the item in the whole system, so we must find another way to identify the item.
Since this requirement is here just because of the Doctrine, we can also let the Doctrine to deal with these artificial identifiers.
The Doctrine can generate a unique identifier by itself, and the only thing needed is a mapped field.
So the item now contains a new field just for the Doctrine.

```php
// Item.php

namespace Simara\Cart\Domain;

class Item
{
    private $generatedId;
    
    // the rest of the class is unchanged
}
```

### Collection

Doctrine doesn't hydrate an object collection as an array, it hydrates
the [PersistentCollection](https://github.com/doctrine/doctrine2/blob/2.6/lib/Doctrine/ORM/PersistentCollection.php).

So the type hint in the cart is not right.
This is important for working with the collection - an array is passed as a copy but an object is passed as a reference.
And what is worse, the persistent collection has so many responsibilities so it would become difficult to understand.

Persistent collection implements the [Collection](https://github.com/doctrine/collections/blob/1.5/lib/Doctrine/Common/Collections/Collection.php)
interface and we can use this fact in the code.
If we declare that the collection is not the `PersistentCollection` but an abstract `Collection`, we can initialize it in the constructor with
an [ArrayCollection](https://github.com/doctrine/collections/blob/1.5/lib/Doctrine/Common/Collections/ArrayCollection.php).
So the type hint is true if the object is new - `ArrayCollection`, and it is also true if the object is hydrated by the Doctrine -`PersistentCollection`.

```php
// Cart.php

namespace Simara\Cart\Domain;

use Doctrine\Common\Collections\ArrayCollection;
use Doctrine\Common\Collections\Collection;

class Cart
{
    /**
     * @var string
     */
    private $id;
    
    /**
     * @var Collection|Item[]
     */
    private $items;
    
    public function __construct(string $id)
    {
        $this->id = $id;
        $this->items = new ArrayCollection();
    }

    // the rest of the class is unchanged
}
```


## Doctrine Repository

The Doctrine repository is an infrastructure for the domain, so it is implemented in the infrastructure layer.

```php
// DoctrineCartRepository.php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartNotFoundException;
use Simara\Cart\Domain\CartRepository;
use TypeError;

class DoctrineCartRepository implements CartRepository
{

    /**
     * @var EntityManager
     */
    private $entityManger;

    public function __construct(EntityManager $entityManger)
    {
        $this->entityManger = $entityManger;
    }

    public function add(Cart $cart): void
    {
        $this->entityManger->persist($cart);
    }

    private function find(string $id): ?Cart
    {
        return $this->entityManger->find(Cart::class, $id);
    }

    public function get(string $id): Cart
    {
        return $this->getThrowingException($id);
    }

    private function getThrowingException(string $id): Cart
    {
        try {
            return $this->find($id);
        } catch (TypeError $e) {
            throw new CartNotFoundException();
        }
    }

    public function remove(string $id): void
    {
        $cart = $this->getThrowingException($id);
        $this->entityManger->remove($cart);
    }
}
```

### Mapping

The Doctrine mapping belongs also to the infrastructure as do the repository.
Using PHP annotations is not preferred.
Mapping should not be part of the domain because we want to have the domain infrastructure-free if possible.
It also mixes the PHP domain language with the mapping annotation language.
Static PHP mapping is much better, but it is a bit verbose and difficult to read.
YAML seems to be a good choice, but it is deprecated in future Doctrine versions.
Finally, the XML format seems to be fine and  it is also supported.
So our final choice is XML.

```xml
<!-->Cart.xml<-->
<doctrine-mapping>
    <entity name="Simara\Cart\Domain\Cart">
        <id name="id" type="string" />

        <many-to-many field="items" target-entity="Item">
            <cascade>
                <cascade-all />
            </cascade>
            <join-table name="cart_item">
                <join-columns>
                    <join-column name="cart_id" referenced-column-name="id"/>
                </join-columns>
                <inverse-join-columns>
                    <join-column name="item_id" referenced-column-name="generatedId" on-delete="CASCADE" />
                </inverse-join-columns>
            </join-table>
        </many-to-many>
    </entity>
</doctrine-mapping>
```

```xml
<!-->Item.xml<-->
<doctrine-mapping>
    <entity name="Simara\Cart\Domain\Item">
        <id name="generatedId" type="integer">
            <generator strategy="AUTO" />
        </id>

        <field name="productId" type="string" />
        <field name="amount" type="integer" />

        <embedded name="unitPrice" class="Price" />
    </entity>
</doctrine-mapping>
```

```xml
<!-->Price.xml<-->
<doctrine-mapping>
    <embeddable name="Simara\Cart\Domain\Price">
        <field name="withVat" type="float" />
    </embeddable>
</doctrine-mapping>
```

## System Tests

Because we already have a repository test from [previous article](../ddd_repository/repository.md),
it should be possible to write a Doctrine test only by extending the abstract test.
We test against a real database so we can catch problems that we face in the production environment.
The code below allows us to test against MySQL and PostgreSQL.

```php
// DoctrineCartRepositoryTest.php

namespace Simara\Cart\Infrastructure;

use Doctrine\ORM\EntityManager;
use PHPUnit\Framework\Assert;
use Simara\Cart\Domain\Cart;
use Simara\Cart\Domain\CartRepository;
use Simara\Cart\Domain\Item;
use Simara\Cart\Domain\Price;
use Simara\Cart\Utils\EntityManagerFactory;
use Simara\Cart\Utils\ConnectionManager;

class DoctrineCartRepositoryTest extends CartRepositoryTest
{
    /**
     * @var EntityManager
     */
    private $entityManager;

    protected function createRepository(): CartRepository
    {
        return new DoctrineCartRepository($this->entityManager);
    }

    protected function flush(): void
    {
        $this->entityManager->flush();
        $this->entityManager->clear();
    }

    protected function setUp()
    {
        ConnectionManager::dropAndCreateDatabase();
        $connection = ConnectionManager::createConnection();
        $this->entityManager = EntityManagerFactory::createEntityManager($connection, [Cart::class, Item::class]);
        parent::setUp();
    }

    protected function tearDown()
    {
        parent::tearDown();
        $this->entityManager->getConnection()->close();
    }

    public function testItemsAreRemovedWithCart() {
        $cart = new Cart('1');
        $cart->add('1', new Price(10), 1);
        $repository = $this->createRepository();
        $repository->add($cart);
        $this->flush();

        $repository->remove('1');
        $this->flush();

        $queryBuilder = $this->entityManager->createQueryBuilder();
        $queryBuilder->from(Item::class, 'i')
            ->select('i');
        $query = $queryBuilder->getQuery();
        $result = $query->getResult();
        Assert::assertCount(0, $result);
    }
}
```

### Database Connection and Entity Manager

In the beginning, we need an empty database.
This is done by the [ConnectionManager](https://github.com/simara-svatopluk/cart/blob/doctrine-repository/tests/Utils/ConnectionManager.php).
It uses a dirty trick to get database credentials – it reads global variables.
These variables are defined in the [phpunit.xml](https://github.com/simara-svatopluk/cart/blob/doctrine-repository/phpunit.xml).
This is probably the worst part of the whole project, but it allows us to test against a real database.

We use the [EntityManagerFactory](https://github.com/simara-svatopluk/cart/blob/doctrine-repository/tests/Utils/EntityManagerFactory.php)
to create the `EntityManager`.
The factory defines the mapping location, forbids proxy generation and returns the `EntityManager`.
It also uses the `SchemaTool` to create a database schema.

In this test, we also implement the `flush` method.
It flushes the `EntityManager` and also cleans it forcing database reading.
This method also simulates the persistence wrapper and a new life of the system.

After the test finishes, we have to also close the database connection, it is done in `tearDown` method.

### Doctrine-Specific Tests

We can write a Doctrine-specific test if it makes sense.
It may happen that we have a trouble with mapping and we want to make sure everything is ok.
We check that there isn't any mess in the database after the cart is removed in the last test.

### SQLite Memory Tests

We prepared also SQLite tests because they are fast and help us to find early mapping problems.
Creating the SQLite memory connection is easy because it doesn't require any credentials.

```php
class DoctrineCartRepositoryTest extends CartRepositoryTest
{
	// ...

    protected function setUp()
    {
        $this->entityManager = EntityManagerFactory::createSqliteMemoryEntityManager([Cart::class, Item::class]);
        parent::setUp();
    }

    // ...
```

SQLite testing is also used in the pehapkari.cz tests because it runs fast and doesn't require a real database.

## Integration

We have some integration in tests but it is not a part of the main code.
The reason is that we have no idea how the real project is integrated.
The real project can run on a framework or on a custom integration.
Tests are testing only what they are supposed to test.
The cart aggregate is unit-tested, the Doctrine repository is system tested.
In a real project, we may have also end-to-end tests which prepare the environment, integrate the project and run tests from the entry point to the infrastructure and back.
But these tests don't belong here.

## TL;DR

The infrastructure can influence the implemented model.
The Doctrine repository is the cart repository implementation with mapping in separated files.
Everything is in the infrastructure layer.
We can test the Doctrine repository against a real database.

## Complete code

* https://github.com/simara-svatopluk/cart/tree/doctrine-repository **doctrine-repository** tag only
