# DDD Promised to Tackle Complexity. Event Sourcing Delivered

## TL;DR
* I followed DDD to tackle complexity, but somehow the software kept getting more complex over time.
* The problem is the tactical part of DDD, especially the aggregate.
* The aggregate turned out to be a mixture of concepts, so it is difficult to change when new features come.
* Event Sourcing splits it into two concepts - write and read models - so reacting to new features is easier.

## What DDD is for me
DDD for me is always the language. Language that carries knowledge, language that connects people, language that is an anchor in the code.

But language is just a few pages of the famous DDD book [1].
The book also taught us HOW to achieve it - the tactical part of DDD.

## Almost a decade with Domain-Driven Design
I was lucky to practice DDD for almost a decade.
Long modeling sessions with great colleagues, finding value objects,
entities and their identities. Defining boundaries by aggregates and ultimately bounded contexts.
The domain clearly separated from infrastructure, pure enough to be easily reused in CLI or web applications.

I was very happy that I not only understood OOP, but could also implement it easily.
Diagram and code became just two representations of the reality I worked in.

I shared my point of view in my earlier articles [2].

## Event Sourcing projects
Then I was involved in financial Event Sourcing projects, and they were different.

Event Sourcing is based around events, and streams of events are the source of truth.
But the purpose of this article isn't to explain Event Sourcing itself [3].

What did I see? Just plain (public?!) data structures everywhere - events.
And event streams as first-class citizens. That's all?

I couldn't believe there was no encapsulation around events... I wanted to introduce it, and my colleagues stopped me.
My OOP world was falling apart.

But it was a good kind of falling apart - the alpha and omega of the system were streams of events.
Some streams were folded (reduced) into the write model - to guard invariants and produce more events;
some streams were consumed and folded into persistent read models.

Simple.

The projects had been started by people who didn't care about DDD. They "just" did Event Sourcing.
Yet somehow I saw the best DDD of my life.
Events were packed with domain terms, events were basically domain terms expressed in code - with almost no boilerplate.
Domain invariants crystal clear in the write model, many single-purpose projections...

I did CQRS before, but it was mind-blowing how nice it is with Event Sourcing.

## Looking back
After two years with Event Sourcing, I looked back at my previous projects, and what did I see?

Aggregate as the central point of the system.
Aggregate as the keeper of invariants.
Aggregate as the ultimate holder of information.

As the project evolves, we also implement new features.

But the aggregate has its shape, and sometimes it's difficult to add a new feature.

In such a case we can remodel. Do a proper migration, think about all edge cases, and implement the feature really properly. This is costly.

Or we go with a compromise - between domain clarity, performance and ease of migration.
And this compromise will keep making our life difficult in the future.

The root cause, in my opinion, is that one shape of the aggregate does two jobs.
It guards invariants, and it answers questions.
Plus each new data export or data aggregation means another workaround on top of the aggregate.
Once the shape is decided at the beginning of the project, it's difficult to react to changes, to new needs.

Event Sourcing separates the two jobs.
We store events - everything that happened in our system.
Once we have the events, the write model and the read models can evolve independently, because they are not mixed together.

The tactical patterns of DDD made us decide the shape early.
Event Sourcing gives us the freedom to postpone such important decisions.

## Is DDD still important?
DDD still matters. In the age of AI, maybe even more than before.
But just the first few pages of Evans' book [1]. Just the language.

Event Sourcing is Domain-Driven Design distilled, for me.
DDD promised to tackle complexity. Event Sourcing delivered.

## Why Event Sourcing matters
Change is cheaper, in my experience.
The write model is rebuilt from events for every decision, so changing its structure needs no data migration.
Read models can be thrown away and replayed from history.

Event Sourcing doesn't forget. It doesn't forget data, and it doesn't forget decisions - events answer the question WHY.
Accounting is append-only for a reason.

AI is capable of working with both styles - traditional and event-sourced.
But with Event Sourcing I have more confidence that it made the intended changes - AI operates on the level of language, and, well... events are the language itself. Each change also touches only a limited part of the system.

## Event Sourcing costs
Switching to Event Sourcing isn't for free.

The point of view on the model is different and new.
You'll make new mistakes, and it will take time to model properly in the new style.

It's also a new tool. Like with every new tool, you'll face new, unexpected problems.
Problems that you didn't have with the "traditional" approach, like event versioning, removing data, or dealing with a long history.

## Contact
Thinking about Event Sourcing?
Contact me and I'll help you - https://svatasimara.cz/

## References

[1] EVANS, Eric. *Domain-driven design: tackling complexity in the heart of software*. Boston: Addison-Wesley, 2004. ISBN 0-321-12521-5.

[2] ŠIMARA, Svatopluk. *Domain-Driven Design, part 4 — Implementation* [online]. 2020 [2026-11-17]. Available at: https://svatasimara.medium.com/domain-driven-design-part-4-implementation-311b08f25107

[3] FOWLER, Martin. *Event Sourcing* [online]. 2005 [2026-11-17]. Available at: https://martinfowler.com/eaaDev/EventSourcing.html
