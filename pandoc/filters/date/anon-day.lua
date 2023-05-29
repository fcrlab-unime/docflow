function Span(span)
if span.classes:includes('personaldata') and span.classes:includes('date') and span.classes:includes('day') then
  local content_string = pandoc.utils.stringify(span.content)
  span.content = pandoc.Str("xx")
  local value, position = span.classes:find('day')
  span.classes:remove(position)
  local value, position = span.classes:find('date')
  span.classes:remove(position)
end
return span
end