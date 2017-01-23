# How to simplify object model

## Task

It doesn't make sense to explain modeling on *Foo, Bar*. Explaining in general terms would be overcomplicated. So we'll start with real world task.

We are going to build a content application that deals with pages and has navigation menu.

Page is a content holder and that's all for now. We'll want just show page content, create it and change it's content.

Menu is more interesting. It's a hirearchical navigation structure that contain items. Each item has a title and links to one page. Menu will contain at most tenths of items. We want to create menu and show it (as an array is suitable for now). We want to be able add item on a certain level, move item within level to a certain position and move item under a different parent item. 

## Model

Let's identify concepts, responsibilities and relations.

![alt text](resource/simplify_object_model-1.png "First model")

Page is a content holder, Menu and Item will together hold menu logic. LinkGenerator is an interface that will generate links for menu items, implementation will be acording our favorite framework.

This is a place where lot of us ends. But we have objects that tell stories, not relation database. Let's continue.

Link generator generate links for Pages by their identifier. Right now we didn't decided yet what will be the Page identifier.

## Concrete relations

Associations are naturally two ways. But it's really difficult to think, work and programm with two ways relations. We'll try to simplify them if possible.

What is a Page? It is a content holder, not more. So, Page will have no idea about menu Item, this relation is one way, hoorray!

Menu holds all it's Items, it has responsibility for logic above more Items and also for generating hierarchical structure. Menu must know it's items, but should Item know about it's Menu? Nope, this relation is also one way, good.

Menu Items keep hierarchical structure. There are couple ways to model this, in this model we will assume that each Item can have a parent Item, and if not, it is a root Item. Should be this parent/child relation two way? Naturally yes. But it is difficult to implement, it a one way enough? Well, it can be. Fine, if one way is enough, we'll use it, we can find this not suitable, so maybe we'll change it later.

![alt text](resource/simplify_object_model-2.png "Model with simplified relations")

## Aggregates

Aggregate is a group of objects that live and die together. Aggreate parts do not have sense alone, they make sense only if we have all of them together. 

You cannot access inner objects of aggreate themself. You have to always use one  main object, so called Aggregate Root. Thanks to this, it is easy to understand Aggregate and it is also easy to refactor aggregate inside.

Page is a content holder, so it is an aggregate and aggregate root itself.

Can menu Item live without Menu? Nope, because logic is in both, in Menu and also in Item. Menu and Items are an aggregate. Menu is also natural entry point, aggregate root. This helps us encapsulate Item concept for Menu user, so Menu will be easier to understand. Encapsulation also strengthens asociation between Menu and Item, it can be now composition, because Item without menu doesn't make sense and Menu without Items is also non-sense.

![alt text](resource/simplify_object_model-3.png "Model with aggregates")

## Separated aggregates

Aggregates must be understant whole or not, must be created whole or not, must be persisted whole or not, must be retrivied from repository (database or storage) whole or not at all.

Right now we have a problem between Page and Menu. We cannot work with Menu without related Pages. If we want to work with persisted Menu, we have to load also relevant Pages. This is bad. Let's separate them.

![alt text](resource/simplify_object_model-4.png "Model with separated aggregates")

What is Item responsibility? One of them is to create link using LinkGenerator. What does it need for creating link? Only Page identification. Why does it know about whole Page? It doesn't have to. Great, we have just separated aggregates!

## Benefits of separated aggregates

* Easy to understand
* Easy to test
* Easy to persist

## Problems

You can ask: What about data integrity? What if I'll delete Page?

This can be sometimes imaginary problem - we don't have delete use case, so in this system it cannot be a problem.

But anyway, whose responsibility is data integrity? Should Page deal with it? Should Menu deal with it? Nope, Page is a content holder, Menu is a navigation.

For example we want to prohibit Page removing when it is in a Menu. Who is responsible for this logic? There are several answers.

You can have an application service that will look for Menus that contain given Page identificator and if they find at least one, they will throw an exception.

You can deal with these use cases in infrastructure - in database you can define foreign keys. But this solution have some issues. It is difficult to test, it rely on infrastructure, database for Page and Menu doesn't have to be the same...


## TL;DR

