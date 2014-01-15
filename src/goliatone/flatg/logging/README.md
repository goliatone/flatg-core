## Logging ##

Move to its own composer project. Promote package to:
`goliatone\logging`
Meaning, remove it from FlatG!

#### TODO ####

- Publishers need to be able to unregister themselves from Manager, so that we might register them but still remove them
from pull...
- TypedSet: Data structure to hold many instances of one Type, and proxy method calls to all it's instances. For CompoundPublisher, CompoundFormatter
- There should be an IFilterable, with addFilter, getFilter, hasFilter, removeFilter, and isFiltered.
- Integrate with a ErrorLogger that registers as an error_handler and exception_handler


#### CONFIGURATION ####
Configuration should have different levels:
- Default configuration
- File configuration, global
- File configuration, instance
- Using Logger API.